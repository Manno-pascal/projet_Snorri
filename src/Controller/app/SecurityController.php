<?php

namespace App\Controller\app;

use App\Entity\User;
use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route(path: '/', name: 'app_redirect')]
    public function homeRedirect(): Response
    {
        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/reset-password', name: 'reset_pass')]
    public function resetPassword(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $hasher, EntityManagerInterface $em)
    {
        $user = $userRepository->findOneBy(["resetToken" => $request->query->get('token')]);

        if (!$user) {
            return $this->redirectToRoute('forgotten_password', [Response::HTTP_SEE_OTHER]);
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));
            $user->setResetToken(null);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login', [Response::HTTP_SEE_OTHER]);
        }
        return $this->render('security/resetPassword.html.twig', [
            'form' => $form,
        ]);
    }


    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/forgotten-password', name: 'forgotten_password')]
    public function forgottenPassword(Request                 $request,
                                      UserRepository          $userRepository,
                                      TokenGeneratorInterface $tokenGenerator,
                                      EntityManagerInterface  $entityManager,
                                      MailerInterface         $mailer)
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);
            if ($user) {
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                $emailContent = $this->renderView('security/_reset_mail.html.twig', [
                    'user' => $user,
                    'resetLink' => $url,
                ]);
                try {
                    $email = (new Email())
                        ->from('hi@demomailtrap.com')
                        ->to($user->getEmail())
                        ->subject('Rénitialisation mot de passe - SNORRI')
                        ->html($emailContent);

                    $mailer->send($email);
                } catch (\Exception $exception) {
                    throw new \LogicException("Erreur dans l'envoi du mail, contactez un administrateur.");
                }

                return $this->redirectToRoute('app_login');
            }
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/forgotPassword.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }
}

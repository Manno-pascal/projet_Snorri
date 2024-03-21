<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\Information;
use App\Entity\Message;
use App\Entity\Offer;
use App\Entity\Thread;
use App\Entity\Tool;
use App\Entity\User;
use App\Entity\UserThread;
use App\Entity\UserTool;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractDashboardController
{

    public function __construct(readonly private AdminUrlGenerator $adminUrlGenerator)
    {
    }
    
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(UserCrudController::class)->generateURL();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Snorri V3');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Panneau administration');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);

        yield MenuItem::linkToCrud('Outils', 'fa fa-toolbox', Tool::class);

        yield MenuItem::linkToCrud('Offres', 'fa fa-home', Offer::class);

        yield MenuItem::linkToCrud('Threads', 'fa fa-align-justify', Thread::class);

        yield MenuItem::linkToCrud('Messages', 'fa fa-message', Message::class);

        yield MenuItem::linkToCrud('Informations', 'fa fa-square-rss', Information::class);

        yield MenuItem::linkToCrud('Calendrier', 'fa fa-calendar', Event::class);


    }
}

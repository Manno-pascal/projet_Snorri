<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UploaderHandler
{
    public function __construct(private readonly ParameterBagInterface $parameterBag, private readonly  EntityManagerInterface $entityManager)
    {

    }
    public function upload($file, $instance, $columnName): string
    {
        $originalFilename = $file->getClientOriginalName();
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);

        if ($file && $instance) {
            // Génération d'un nom de fichier unique pour l'image
            $fileName = $columnName. uniqid('_') . '.' . $extension;

            // déplacement du fichier téléchargé dans le dossier de destination
            $uploadDirectory = $this->parameterBag->get('upload_directory') . $columnName;
            $file->move($uploadDirectory, $fileName);

            //Assemblage de la fonction getter correspondant à la bonne instance et la bonne colonne
            $getFunctionName = 'get' . ucfirst($columnName);
            //Vérification de si il y a déjà une image présente
            if ($instance->$getFunctionName() !== null && $instance->$getFunctionName() !== "") {
                //Suppression de l'image déjà existante
                unlink($this->parameterBag->get('upload_directory') . $columnName . "/"
                    . $instance->$getFunctionName());
            }

//Assemblage de la fonction setter correspondant à la bonne instance et la bonne colonne
            $setFunctionName = 'set' . ucfirst($columnName);
// Enregistrement du nom en bdd
            $instance->$setFunctionName($fileName);

// Mise à jour de la BDD
            $this->entityManager->persist($instance);
            $this->entityManager->flush();

//Assemblage de la fonction getter de l'url correspondant à la bonne instance et la bonne colonne
            $getFunctionName = 'get' . ucfirst($columnName);
            return call_user_func([$instance, $getFunctionName]);
        }
        return false;
    }
}
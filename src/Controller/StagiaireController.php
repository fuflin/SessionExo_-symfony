<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'app_stagiaire')]

    public function index(EntityManagerInterface $em): Response
    {
        $stagiaires = $em->getRepository(Stagiaire::class)->findAll();
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }


    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]

    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/detailstagiaire.html.twig', [
           'stagiaire' => $stagiaire,
        ]);
    }
}
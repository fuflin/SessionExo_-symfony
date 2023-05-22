<?php

namespace App\Controller;

use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    #[Route('/programme', name: 'app_programme')]

    public function index(EntityManagerInterface $em): Response
    {
        $programmes = $em->getRepository(Programme::class)->findAll();
        return $this->render('programme/index.html.twig', [
            'programmes' => $programmes,
        ]);
    }
}

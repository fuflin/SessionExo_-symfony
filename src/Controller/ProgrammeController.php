<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Form\ProgrammeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/programme/add/{sessionID}', name: 'add_programme')]
    #[Route('/programme/{id}/edit/{sessionID}', name: 'edit_programme')]

    public function add(EntityManagerInterface $em, Programme $programme = null, Request $request, $sessionID = null): Response
    {
        if(!$programme){

            $programme = new Programme();
        }

        $session= $em->getRepository(Session::class)->find($sessionID);

        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        // si (on a bien appuyer sur submit && que les infos du formulaire sont conformes au filter input qu'on aura mis)
        if ($form->isSubmitted() && $form->isValid()) {

            $programme->setSession($session);

            $programme = $form->getData(); // hydratation avec données du formulaire / injection des valeurs saisies dans le form

            $em->persist($programme); // équivalent du prepare dans PDO
            $em->flush(); // équivalent de insert into (execute) dans PDO

            return $this->redirectToRoute('show_session', ['id'=>$sessionID]);
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('programme/add.html.twig', [
            'form' => $form->createView(),
            'edit' => $programme->getId()]); // création du formulaire
    }

}

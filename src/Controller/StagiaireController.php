<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\FormStagiaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    // fonction pour afficher la listes des stagiaires
    #[Route('/stagiaire', name: 'app_stagiaire')]

    public function index(EntityManagerInterface $em): Response
    {
        $stagiaires = $em->getRepository(Stagiaire::class)->findAll();
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

    // fonction pour ajouter un stagiaire
    #[Route('/stagiaire/add', name: 'add_stagiaire')]

    public function add(EntityManagerInterface $em, Request $request): Response
    {

            $stagiaire = new Stagiaire();

        $form = $this->createForm(FormStagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        // si (on a bien appuyer sur submit && que les infos du formalaire sont conformes au filter input qu'on aura mis)
        if ($form->isSubmitted() && $form->isValid()) {

            
            $stagiaire = $form->getData(); // hydratation avec données du formulaire / injection des valeurs saisies dans le form
            
            $em->persist($stagiaire); // équivalent du prepare dans PDO
            $em->flush(); // équivalent de insert into (execute) dans PDO
            
            dd($stagiaire);        
            return $this->redirectToRoute('app_stagiaire');
        }
        // vue pour afficher le formulaire d'ajout
        return $this->render('stagiaire/add.html.twig', [
            'formAddStagiaire' => $form->createView(), ]); // création du formulaire
    }



    // fonction pour afficher les détails d'un stagiaire
    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]

    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/detailstagiaire.html.twig', [
           'stagiaire' => $stagiaire,
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]

    public function index(EntityManagerInterface $em): Response
    {
        $sessions = $em->getRepository(Session::class)->findAll();
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('/session/add', name: 'add_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]

    public function add(EntityManagerInterface $em, Session $session = null, Request $request): Response
    {
        if(!$session){

            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        // si (on a bien appuyer sur submit && que les infos du formalaire sont conformes au filter input qu'on aura mis)
        if ($form->isSubmitted() && $form->isValid()) {

            $session = $form->getData(); // hydratation avec données du formulaire / injection des valeurs saisies dans le form

            $em->persist($session); // équivalent du prepare dans PDO
            $em->flush(); // équivalent de insert into (execute) dans PDO

            return $this->redirectToRoute('app_session');
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('session/add.html.twig', [
            'formAddSession' => $form->createView(),
            'edit' => $session->getId()]); // création du formulaire
    }

    // fonction pour supprimer un session
    #[Route('/session/{id}/delete', name: 'delete_session')]

    public function delete(EntityManagerInterface $em, Session $session): Response
    {

        $em->remove($session);
        $em->flush();

        return $this->redirectToRoute('app_session');
    }

    #[Route('/session/{id}', name: 'show_session')]
    // affichage des stagiaires non inscrit à une session
    public function show(EntityManagerInterface $em, Session $session): Response
    {
        $stagiaires = $em->getRepository(Stagiaire::class)->showStagInSession($session); //on utilise la fonction précedement créer dans le repository pour récupérer les stagiaires
        return $this->render('session/detailSession.html.twig', [
           'session' => $session,
           'stagiaires' => $stagiaires
        ]);
    }

    #[Route('/session/{id}/addStagiaire/{idStagiaire}', name: 'add_to_session')]
    #[Route('/session/{id}/removeStagiaire/{idStagiaire}', name: 'remove_session')]

    // pour la fonction on met en argument id pour la session et idStagiaire pour le stagiaire à modifier
    public function modifySession(EntityManagerInterface $em, Session $session, $id, $idStagiaire){

        $stagiaire = $em->getRepository(Stagiaire::class)->find($idStagiaire);

        if($session->getStagiaires()->contains($stagiaire)){

            $session->removeStagiaire($stagiaire);
        } else {

            $session->addStagiaire($stagiaire);
        }

        $em->flush();
        return $this->redirectToRoute('show_session', ['id' => $id]);

    }

}

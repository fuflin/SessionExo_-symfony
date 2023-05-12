<?php

namespace App\Controller;

use App\Entity\Session;
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

    public function add(EntityManagerInterface $em, Request $request): Response
    {

            $session = new Session();

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
            'formAddSession' => $form->createView(), ]); // création du formulaire
    }


    #[Route('/session/{id}', name: 'show_session')]

    public function show(Session $session): Response
    {
        return $this->render('session/detailSession.html.twig', [
           'session' => $session,
        ]);
    }

}

<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\ModuleType;
use App\Entity\ModuleSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(): Response
    {
        return $this->render('module/index.html.twig', [
            'controller_name' => 'ModuleController',
        ]);
    }

    #[Route('/module/add', name: 'add_module')]
    #[Route('/module/{id}/edit', name: 'edit_module')]

    public function add(EntityManagerInterface $em, ModuleSession $module = null, Request $request): Response
    {
        if(!$module){

            $module = new ModuleSession();
        }

        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        // si (on a bien appuyer sur submit && que les infos du formulaire sont conformes au filter input qu'on aura mis)
        if ($form->isSubmitted() && $form->isValid()) {

            $module = $form->getData(); // hydratation avec données du formulaire / injection des valeurs saisies dans le form

            $em->persist($module); // équivalent du prepare dans PDO
            $em->flush(); // équivalent de insert into (execute) dans PDO

            return $this->redirectToRoute('app_category');
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('module/add.html.twig', [
            'formAddmodule' => $form->createView(),
            'edit' => $module->getId()]); // création du formulaire
    }

    // fonction pour supprimer un module
    #[Route('/module/{id}/delete', name: 'delete_module')]

    public function delete(EntityManagerInterface $em, ModuleSession $module): Response
    {

        $em->remove($module);
        $em->flush();

        return $this->redirectToRoute('app_category');
    }

}


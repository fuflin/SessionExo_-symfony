<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]

    public function index(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/add', name: 'add_category')]
    #[Route('/category/{id}/edit', name: 'edit_category')]

    public function add(EntityManagerInterface $em, Category $category = null, Request $request): Response
    {
        if(!$category){

            $category = new Category();
        }

            $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        // si (on a bien appuyer sur submit && que les infos du formalaire sont conformes au filter input qu'on aura mis)
        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData(); // hydratation avec données du formulaire / injection des valeurs saisies dans le form

            $em->persist($category); // équivalent du prepare dans PDO
            $em->flush(); // équivalent de insert into (execute) dans PDO

            return $this->redirectToRoute('app_category');
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('category/add.html.twig', [
            'formAddCategory' => $form->createView(),
            'edit' => $category->getId()]); // création du formulaire
    }

    // fonction pour supprimer un stagiaire
    #[Route('/category/{id}/delete', name: 'delete_category')]

    public function delete(EntityManagerInterface $em, Category $category): Response
    {

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('app_category');
    }

    // fonction pour afficher les détails du la catégorie
    #[Route('/category/{id}', name: 'show_category')]

    public function show(Category $category): Response
    {
        return $this->render('category/detailCategory.html.twig', [
           'category' => $category,
        ]);
    }


}

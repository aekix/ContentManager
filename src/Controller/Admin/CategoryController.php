<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryUpdateType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/category/admin", name="category_admin")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/admin/create", name="category_admin_create")
     */
    public function create(Request $request, ValidatorInterface $validator)
    {
        $category = new Category();
        $form = $this->createForm(CategoryUpdateType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);
            $this->em->flush();

            return $this->redirectToRoute('category_admin');
        }

        return $this->render('admin/category/category_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category/admin/{id}", name="category_admin_get")
     */
    public function getById(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryUpdateType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setLabel($request->get('label') ?? $category->getLabel());
            $category->setDescription($request->get('description') ?? $category->getDescription());
            $this->em->persist($category);
            $this->em->flush();
            $this->get('session')->getFlashBag()->add(
                'message',
                'Modifications enregistrées'
            );
        }

        return $this->render('admin/category/category_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

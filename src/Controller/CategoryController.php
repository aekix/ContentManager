<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/category", name="api_private_category_")
 */
class CategoryController extends AbstractFOSRestController
{
    private $em;

    public function  __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, ValidatorInterface $validator)
    {
        $category = new Category();

        $category->setLabel($request->get('label'));
        $category->setDescription($request->get('description'));
        $this->em->persist($category);
        $this->em->flush();
        return $this->view($category);
    }

    /**
     * @Route("/{id}", name="get")
     */
    public function  getById(Category $category)
    {
        return $this->view($category);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Category $category, Request $request)
    {
        $category->setLabel($request->get('label') ?? $category->getLabel());
        $category->setDescription($request->get('description') ?? $category->getDescription());
        $this->em->persist($category);
        $this->em->flush();
        return $this->view($category);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Category $category)
    {
        $this->em->remove($category);
        $this->em->flush();
        return $this->view();
    }
}

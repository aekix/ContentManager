<?php

namespace App\Controller;

use App\Repository\ContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ContentRepository $contentRepository)
    {

        $contentsList = $contentRepository->findHomePublishedContents();
        dump($contentsList);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'contentsOrderByDate' => $contentsList,
        ]);
    }
}

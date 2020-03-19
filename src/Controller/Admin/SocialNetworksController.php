<?php

namespace App\Controller\Admin;

use App\Entity\SocialNetworks;
use App\Form\SocialNetworksUpdateType;
use App\Repository\SocialNetworksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/socialNetworks/admin", name="admin_socialNetworks_")
 */
class SocialNetworksController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/all", name="all")
     */
    public function index(SocialNetworksRepository $socialNetworksRepository)
    {
        $socialNetworks = $socialNetworksRepository->findAll();

        return $this->render('admin/socialNetworks/index.html.twig', [
            'socialNetworks' => $socialNetworks
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $category = new SocialNetworks();
        $form = $this->createForm(SocialNetworksUpdateType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($category);
            $this->em->flush();

            return $this->redirectToRoute('admin_socialNetworks_all');
        }

        return $this->render('admin/socialNetworks/socialNetworks_update.html.twig', [
            'form' => $form->createView(),
            'socialNetwork' => null
        ]);
    }

    /**
     * @Route("/{id}", name="edit")
     */
    public function edit(Request $request, SocialNetworks $socialNetwork)
    {
        $form = $this->createForm(SocialNetworksUpdateType::class, $socialNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($socialNetwork);
            $this->em->flush();
            $this->get('session')->getFlashBag()->add(
                'message',
                'Modifications enregistrées'
            );
        }

        return $this->render('admin/socialNetworks/socialNetworks_update.html.twig', [
            'form' => $form->createView(),
            'socialNetwork' => $socialNetwork
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(SocialNetworks $socialNetworks)
    {
        $this->em->remove($socialNetworks);
        $this->em->flush();
        return $this->redirectToRoute('admin_socialNetworks_all');
    }

}

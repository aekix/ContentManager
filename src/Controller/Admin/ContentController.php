<?php

namespace App\Controller\Admin;

use App\Entity\Content;
use App\Form\EditContentType;
use App\Repository\ContentRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/content/admin", name="admin_content_")
 */
class ContentController extends AbstractController
{
    private $contentRepository;
    private $em;

    public function __construct(ContentRepository $contentRepository, EntityManagerInterface $em)
    {
        $this->contentRepository = $contentRepository;
        $this->em = $em;
    }

    /**
     * @Route("/contents", name="list")
     */
    public function contentList(ContentRepository $contentRepository)
    {
        $contents = $contentRepository->findAll();

        return $this->render('admin/content/list.html.twig', [
            'contents' => $contents,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Content $content)
    {
        $content->setEnabled(0);
        $this->em->persist($content);
        $this->em->flush();
        return $this->redirectToRoute('admin_content_edit', ['id' => $content->getId()]);
    }

    /**
     * @Route("/activer/{id}", name="active")
     */
    public function activer(Content $content)
    {
        $content->setEnabled(1);
        $this->em->persist($content);
        $this->em->flush();
        return $this->redirectToRoute('admin_content_edit', ['id' => $content->getId()]);
    }

    /**
     * @Route("/{id}", name="edit")
     */
    public function edit(Content $content, Request $request)
    {
        $form = $this->createForm(EditContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $content->setTitle($data->getTitle());
            $content->setText($data->getText());
            $content->setCategory($data->getCategory());
            $this->em->persist($content);
            $this->em->flush();
            $this->get('session')->getFlashBag()->add(
                'message',
                'Modifications enregistrées'
            );
        }
        return $this->render('admin/content/edit.html.twig', [
            'form' => $form->createView(),
            'action' => 'Modifier',
            'content' => $content,
        ]);
    }
}

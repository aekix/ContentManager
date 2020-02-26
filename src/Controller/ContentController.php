<?php

namespace App\Controller;

use App\Entity\Content;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/content", name="api_private_content_")
 */
class ContentController extends AbstractFOSRestController
{
    private $contentRepository;
    private $em;
    private $passwordEncoder;
    public function __construct(ContentRepository $contentRepository, EntityManagerInterface $em)
    {
        $this->contentRepository = $contentRepository;
        $this->em = $em;
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request, ValidatorInterface $validator)
    {
        $content = new Content();

        $content->setAuthor($this->getUser());
        $content->setEnabled(1);
        $content->setText($request->get('text'));
        $content->setCategory($request->get('lastname'));
        $this->em->persist($content);
        $this->em->flush();
        return $this->view($content);
    }

    /**
     * @Route("/{id}", name="get")
     */
    public function  getById(Content $content)
    {
        return $this->view($content);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Content $content, Request $request)
    {
        $content->setLastname($request->get('lastname') ?? $content->getLastname());
        $content->setFirstname($request->get('firstname') ?? $content->getFirstname());
        $content->setPassword($this->passwordEncoder->encodePassword($content, $request->get('password')?? $content->getPassword()));
        $content->setMail($request->get('mail') ?? $content->getMail());
        $content->setEnabled($request->get('enabled') ?? $content->getEnabled());
        $content->setRoles($request->get('roles') ?? $content->getRoles());
        $this->em->persist($content);
        $this->em->flush();
        return $this->view($content);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Content $content)
    {
        $content->setEnabled(1);
        $this->em->persist($content);
        $this->em->flush();
        return $this->view($content);
    }
}
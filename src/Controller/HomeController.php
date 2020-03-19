<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserUpdateType;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     */
    public function home(ContentRepository $contentRepository)
    {

        $contentsList = $contentRepository->findHomePublishedContents();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'contentsOrderByDate' => $contentsList,
        ]);
    }

    /**
     * @Route("profile", name="profile")
     */
    public function profile(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setLastname($request->get('lastname') ?? $user->getLastname());
            $user->setFirstname($request->get('firstname') ?? $user->getFirstname());
            $user->setPassword($passwordEncoder->encodePassword($user, $request->get('password') ?? $user->getPassword()));
            $user->setMail($request->get('mail') ?? $user->getMail());
            $this->em->persist($user);
            $this->em->flush();
            $this->get('session')->getFlashBag()->add(
                'message',
                'Modifications enregistrées'
            );
        }
        return $this->render('user/user_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

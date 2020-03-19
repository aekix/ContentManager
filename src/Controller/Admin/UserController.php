<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserUpdateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user/admin", name="admin_user_")
 */
class UserController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/all", name="all")
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/disable/{id}", name="disable")
     */
    public function disable(User $user)
    {
        $user->setEnabled(0);
        $this->em->persist($user);
        $this->em->flush($user);
        return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
    }

    /**
     * @Route("/enable/{id}", name="enable")
     */
    public function enable(User $user)
    {
        $user->setEnabled(1);
        $this->em->persist($user);
        $this->em->flush($user);
        return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
    }

    /**
     * @Route("/{id}", name="edit")
     */
    public function edit(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user->setPassword($passwordEncoder->encodePassword($user, $data->getPassword() ?? $user->getPassword()));
            $this->em->persist($user);
            $this->em->flush();
            $this->get('session')->getFlashBag()->add(
                'message',
                'Modifications enregistrées'
            );
        }

        return $this->render('admin/user/user_update.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}

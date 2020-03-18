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
 * @Route("/user/admin", name="admin_user_)
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
     * @Route("/user/admin/{id}", name="edit")
     */
    public function edit(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserUpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setEnabled((Boolean)$request->get('enabled'));
            $user->setLastname($request->get('lastname') ?? $user->getLastname());
            $user->setFirstname($request->get('firstname') ?? $user->getFirstname());
            $user->setPassword($passwordEncoder->encodePassword($user, $request->get('password') ?? $user->getPassword()));
            $user->setMail($request->get('mail') ?? $user->getMail());
            $user->setRoles([$request->get('roles')]);
            $this->em->persist($user);
            $this->em->flush();
            $this->get('session')->getFlashBag()->add(
                'message',
                'Modifications enregistrées'
            );
        }

        return $this->render('admin/user/user_update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

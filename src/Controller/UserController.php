<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
/**
 * @Route("/api/user", name="api_private_user_")
 */
class UserController extends AbstractFOSRestController
{
    private $userRepository;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Rest\Get("/create", name="create")
     */
    public function create(Request $request,ValidatorInterface $validator, PasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $user->setRoles([$request->get('role')]);
        $user->setEnabled(1);
        $user->setMail($request->get('mail'));
        $user->setPassword($passwordEncoder->encodePassword($user, $request->get('password')));
        $user->setFirstname($request->get('firstname'));
        $user->setLastname($request->get('lastname'));
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }
}

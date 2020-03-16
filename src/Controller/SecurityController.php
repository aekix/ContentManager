<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use App\Form\EmailResetType;
use App\Form\NewPasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/forgetPassword", name="forgetPassword")
     */
    public function resetPassword(Request $request, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, UserRepository $userRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(EmailResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['mail' => $form->getData()->getMail()]);
            if (!$user) {
                $this->get('session')->getFlashBag()->add(
                    'warning',
                    'L\'adresse renseignée est inconnue.'
                );
                return $this->redirectToRoute("forgetPassword");
            }
            $user->setToken($tokenGenerator->generateToken());
            // enregistrement de la date de création du token
            $user->setPasswordRequestedAt(new \Datetime());
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Réinitialisation de votre mot de passe'))
                ->setFrom('noreply@ascensia.com')
                ->setTo($user->getMail())
                ->setBody(
                    $this->renderView(
                        'emails/reset_password.html.twig', ['user' => $user]
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $this->get('session')->getFlashBag()->add(
                'message',
                'Un mail a été envoyé à votre adresse email.'
            );

//            return $this->redirectToRoute("login");

        }

        return $this->render('security/reset_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function isRequestInTime(\Datetime $passwordRequestedAt = null)
    {
        if ($passwordRequestedAt === null)
        {
            return false;
        }

        $now = new \DateTime();
        $interval = $now->getTimestamp() - $passwordRequestedAt->getTimestamp();
        $daySeconds = 60 * 10;
        $response = $interval > $daySeconds ? false : $reponse = true;
        return $response;
    }
    /**
     * @Route("/newPassword/{id}/{token}", name="new_password")
     */
    public function resetting(User $user, $token, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // interdit l'accès à la page si:
        // le token associé au membre est null
        // le token enregistré en base et le token présent dans l'url ne sont pas égaux
        // le token date de plus de 10 minutes
        if ($user->getToken() === null || $token !== $user->getToken() || !$this->isRequestInTime($user->getPasswordRequestedAt()))
        {
            throw new AccessDeniedHttpException();
        }
        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            // réinitialisation du token et de la date de sa création à NULL
            $user->setToken(null);
            $user->setPasswordRequestedAt(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('message', "Votre mot de passe a été renouvelé.");
            return $this->redirectToRoute('login');
        }
        return $this->render('security/new_password.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);
            $user->setEnabled(1);
            $user->setRoles(['ROLE_USER']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

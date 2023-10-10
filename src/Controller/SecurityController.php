<?php

namespace App\Controller;

use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher ;
    }

    #[Route('/security', name: 'app_security')]
    public function index(): Response
    {
        return $this->render('Front-Office/security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
             return $this->redirectToRoute('app_user_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Front-Office/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
       // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgotPass', name: 'app_forgot_password')]
    public function forgottenPassword(Request $request,UserRepository $repo,MailerService $mailer,TokenGeneratorInterface $tokenGenerator,ManagerRegistry $rm)
    {
        $form=$this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=$repo->findOneByEmail($form->get('email')->getData());
            if($user){
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $em =$rm->getManager();
                $em->persist($user);
                $em->flush();

                $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                //$context = compact('url', 'user');
                $context = ['url'=>$url , 'user' =>$user];

                $mailer->sendEmail(
                    to: $user->getEmail(),
                    template: 'forgot-password',
                    subject: 'Réinitialisation de mot de passe',
                    context: $context
                );
                return $this->redirectToRoute('app_login');
            }
            return $this->redirectToRoute('app_login');
        }
        return $this->render('Front-Office/security/forgot-password.html.twig', [
            'controller_name' => 'SecurityController',
            'form'=>$form->createView()

        ]);
    }

    #[Route('/resetPass{token}', name: 'app_reset_password')]
    public function resetPass($token , UserRepository $repo,Request $request,ManagerRegistry $rm,MailerService $mailer): Response
    {
        $user = $repo->findOneByResetToken($token);

        if($user) {
            $form = $this->createForm(ResetPasswordFormType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $user->setResetToken('');
                $user->setPassword($this->userPasswordHasher->hashPassword($user, $form->get('password')->getData()));
                $em=$rm->getManager();
                $em->persist($user);
                $em->flush();

                $context = ['user' =>$user];

                $mailer->sendEmail(
                    to: $user->getEmail(),
                    template: 'confirmation-password',
                    subject: ' Confirmation du changement de mot de passe',
                    context: $context
                );

                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('Front-Office/security/password-reset.html.twig', [
            'controller_name' => 'SecurityController',
            'form'=>$form->createView()
        ]);
    }


}

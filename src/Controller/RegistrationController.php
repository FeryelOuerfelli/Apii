<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormMedecinType;
use App\Form\EditFormUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher ;
    }

    #[Route('/registerPatient', name: 'app_registration_patient')]
    public function registerPatient(Request $request,ManagerRegistry $rm,MailerService $mailer)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_PATIENT']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();

            $context = ['user' =>$user];

            $mailer->sendEmail(
                to: $user->getEmail(),
                template: 'confirmation-register',
                subject: ' Confirmation de création de compte',
                context: $context
            );

            $mailer->sendEmail(
                to: "admin20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-patient.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/registerMedecin', name: 'app_registration_doctor')]
    public function registerDoctor(Request $request,ManagerRegistry $rm,MailerService $mailer)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_MEDECIN']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();
            $context = ['user' =>$user];

            $mailer->sendEmail(
                to: $user->getEmail(),
                template: 'confirmation-register',
                subject: ' Confirmation de création de compte',
                context: $context
            );
            $mailer->sendEmail(
                to: "admin20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-doctor.html.twig', [

            'form' => $form->createView()
        ]);
    }
    #[Route('/registerEmploye', name: 'app_registration_employe')]
    public function registerEmploye(Request $request,ManagerRegistry $rm,MailerService $mailer)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_EMPLOYE']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();
            $context = ['user' =>$user];

            $mailer->sendEmail(
                to: $user->getEmail(),
                template: 'confirmation-register',
                subject: ' Confirmation de création de compte',
                context: $context
            );
            $mailer->sendEmail(
                to: "admin20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-employe.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/registerPharmacien', name: 'app_registration_pharmacien')]
    public function registerPharmacien(Request $request,ManagerRegistry $rm,MailerService $mailer)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_PHARMACIEN']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();
            $context = ['user' =>$user];

            $mailer->sendEmail(
                to: $user->getEmail(),
                template: 'confirmation-register',
                subject: ' Confirmation de création de compte',
                context: $context
            );
            $mailer->sendEmail(
                to: "admin20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');

        }
        return $this->render('Front-Office/registration/register-pharmacien.html.twig', [

            'form' => $form->createView()
        ]);
    }


    #[Route('/registerAssureur', name: 'app_registration_assureur')]
    public function registerAssureur(Request $request,ManagerRegistry $rm,MailerService $mailer)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_ASSUREUR']);
            $user->setDateDeCreation(new \DateTime());
            $user->setEtat("non valide");
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();
            $context = ['user' =>$user];

            $mailer->sendEmail(
                to: $user->getEmail(),
                template: 'confirmation-register',
                subject: ' Confirmation de création de compte',
                context: $context
            );
            $mailer->sendEmail(
                to: "admin20@gmail.com",
                template: 'confirmation-register-admin',
                subject: ' Nouveau compte utilisateur créé',
                context: $context
            );
            return $this->redirectToRoute('app_login');
            
        }
        return $this->render('Front-Office/registration/register-assureur.html.twig', [

            'form' => $form->createView()
        ]);
    }

    #[Route('/registerAdmin', name: 'app_registration_admin')]
    public function registerAdmin(Request $request,ManagerRegistry $rm)
    {
            $user = new User();
            $user->setEmail("admin20@gmail.com");
            // Encode the new users password
            $user->setPassword($this->userPasswordHasher->hashPassword($user,"admin"));
            $user->setNom("admin");
            $user->setPrenom("admin");
            // Set their role
            $user->setRoles(['ROLE_ADMIN']);

            $user->setDateDeCreation(new \DateTime());
            // Save
            $em =$rm->getManager();
            $em->persist($user);
            $em->flush();

        return $this->redirectToRoute('app_login');
    }

}

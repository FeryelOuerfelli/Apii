<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormEmployeType;
use App\Form\EditFormUserType;
use App\Form\SearchFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\MaterielRepository;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;



#[
    Route('admin'),
    IsGranted ('IS_AUTHENTICATED_FULLY')
]
class UtilisateurController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function admin(UserRepository $repo , MaterielRepository $materielRepository): Response
    {
        $currentuser = $this->getUser();
        $nbEmployes = $repo->countUsersByRole('ROLE_EMPLOYE');
        $nbFemmes = $repo->countUsersBySexe('Femme');
        $nbHommes = $repo->countUsersBySexe('Homme');
        $nbmateriels = $materielRepository->count([]);
        $nbmaterielspanne = $materielRepository->countMaterielbyetat('En Panne');
        $nbmaterielsstock = $materielRepository->countMaterielbyetat('En Stock');
        $nbmaterielsutilisation = $materielRepository->countMaterielbyetat('En Utilisation');


        return $this->render('Back-Office/DashboardAdmin.html.twig', [
            'controller_name' => 'UtilisateurController',
            'user'=>$currentuser,
            'nbE'=>$nbEmployes,
            'nbF'=>$nbFemmes,
            'nbH'=>$nbHommes,
            'nbMat'=>$nbmateriels,
            'nbMatPanne'=>$nbmaterielspanne,
            'nbMatUtilis'=>$nbmaterielsutilisation,
            'nbMatStock'=>$nbmaterielsstock,





        ]);
    }

  

    #[Route('/newUser', name: 'add_utilisateur')]
    public function add_utilisateur(ManagerRegistry $rg, Request $req):Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $result = $rg->getManager();
            $result->persist($user);
            $result->flush();
        }

        return $this->render('utilisateur/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/validate/{id}', name: 'validate_utilisateur')]
    public function validate_utilisateur($id,ManagerRegistry $rg, Request $req,UserRepository $repo,MailerService $mailer):Response
    {
        $user = $repo->find($id);
        $user->setEtat("valide");
        $result = $rg->getManager();
        $result->persist($user);
        $result->flush();

        $context = ['user' =>$user];

        $mailer->sendEmail(
            to: $user->getEmail(),
            template: 'confirmation-validation',
            subject: ' Validation Compte',
            context: $context
        );

        if (in_array('ROLE_EMPLOYE', $user->getRoles(), true)) {
            return $this->redirectToRoute('list_employes');
        
        }else {
            return $this->redirectToRoute('app_admin');
        }
    }

    #[Route('/bloquer/{id}', name: 'bloquer_utilisateur')]
    public function bloquer_utilisateur($id,ManagerRegistry $rg, Request $req,UserRepository $repo,MailerService $mailer):Response
    {
        $user = $repo->find($id);
        $user->setIsBlocked(true);
        $result = $rg->getManager();
        $result->persist($user);
        $result->flush();

        
            if (in_array('ROLE_EMPLOYE', $user->getRoles(), true)){
            return $this->redirectToRoute('list_employes');
       
        }else {
            return $this->redirectToRoute('app_admin');
        }
    }


    #[Route('/debloquer/{id}', name: 'debloquer_utilisateur')]
    public function debloquer_utilisateur($id,ManagerRegistry $rg, Request $req,UserRepository $repo,MailerService $mailer):Response
    {
        $user = $repo->find($id);
        $user->setIsBlocked(false);
        $result = $rg->getManager();
        $result->persist($user);
        $result->flush();

        
        if (in_array('ROLE_EMPLOYE', $user->getRoles(), true)){
            return $this->redirectToRoute('list_employes');
       
        }else {
            return $this->redirectToRoute('app_admin');
        }
    }

    #[Route('/updateUser/{id}', name: 'update_utilisateur')]
    public function update_utilisateur($id, ManagerRegistry $rg, Request $req, UserRepository $repo, SluggerInterface $slugger): Response
    {
        $user = $repo->find($id);
        $form = $this->createForm(EditFormUserType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('utilisateurs_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setImage($newFilename);
            }
            $result = $rg->getManager();
            $result->persist($user);
            $result->flush();
        }

        return $this->render('Back-Office/Profile-User.html.twig', [
            'form' => $form->createView(),
            'user'=>$user
        ]);

    }

   

    #[Route('/updateEmp/{id}', name: 'update_employe')]
    public function update_employe($id, ManagerRegistry $rg, Request $req, UserRepository $repo, SluggerInterface $slugger): Response
    {
        $user = $repo->find($id);

        $form = $this->createForm(EditFormEmployeType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('utilisateurs_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setImage($newFilename);
            }
            $result = $rg->getManager();
            $result->persist($user);
            $result->flush();

        }

        return $this->render('Back-Office/Profile-Employe.html.twig', [
            'form' => $form->createView(),
            'user'=>$user
        ]);
    }


    #[Route('/removeUser/{id}', name: 'remove_utilisateur')]
    public function remove_utilisateur($id, ManagerRegistry $rg, UserRepository $repo,Request $req): Response
    {
        $user = $repo->find($id);
        $result = $rg->getManager();
         $materiels = $user->getMateriels();
         $admin = $repo->findOneBy(['email' => 'admin20@gmail.com']);
            
            // Dissociez les matériels de l'employé en définissant leur employé sur null
            foreach ($materiels as $materiel) {
                $materiel->setAffectation($admin);
                $materiel->setEtat("En Stock");
            }
        
        $result->remove($user);
        $result->flush();

        if (in_array('ROLE_EMPLOYE', $user->getRoles(), true)) {
            return $this->redirectToRoute('list_employes');
    
        }else {
            return $this->redirectToRoute('app_admin');
        }
    }

   
    #[Route('/listEmployes/{page?1}/{nbre?5}', name: 'list_employes')]
    public function list_employe(UserRepository $repo,Request $req,$nbre,$page):Response
    {
        $nbEmployes = $repo->countUsersByRole('ROLE_EMPLOYE');
        $nbrePage = ceil($nbEmployes / $nbre) ;
        $users = $repo->findUsersByRole($page,$nbre,'ROLE_EMPLOYE');

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $users = $repo->findUsersBySearchTerm($searchTerm);
        }
        return $this->render('Back-Office/list-employes.html.twig', [
            'users' => $users,
            'form'=>$form->createView(),
            'isPaginated'=>true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }

   

   
    
 
    }


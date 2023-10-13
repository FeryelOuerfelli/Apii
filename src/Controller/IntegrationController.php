<?php

namespace App\Controller;

use App\Form\EditFormUserType;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Form\SearchFormType;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IntegrationController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('Front-Office/Landing.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }


   
    #[Route('/listEmployes/{page?1}/{nbre?5}', name: 'app_list_employes')]
    public function listEmployes(UserRepository $repo,Request $req,$nbre,$page): Response
    {
        $user=$this->getUser();
        $nbEmployes = $repo->countUsersByRole('ROLE_EMPLOYE');
        $nbrePage = ceil($nbEmployes / $nbre) ;
        $employes = $repo->findUsersByRole($page,$nbre,'ROLE_EMPLOYE');

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() ) {
            $searchTerm = $form->getData();
            $employes= $repo->findUsersBySearchTerm($searchTerm);
        }
        return $this->render('Front-Office/profile/list-employes1.html.twig', [
            'controller_name' => 'ProfileController',
            'user'=>$user,
            'employes'=>$employes,
            'form'=>$form->createView(),
            'isPaginated'=>true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }


    #[Route('/connect', name: 'app_choose_profile')]
    public function profile(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_home');
        }
        return $this->render('Front-Office/choose_profile.html.twig', [
            'controller_name' => 'IntegrationController',
        ]);
    }



}

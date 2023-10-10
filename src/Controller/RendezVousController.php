<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Entity\RendezVous;
use App\Form\RendezVousRechercheType;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

#[Route('/rendezVous'),
    IsGranted ('IS_AUTHENTICATED_FULLY')
]
class RendezVousController extends AbstractController
{
    #[Route('/show/{page?1}/{nbre?5}', name: 'app_rendez_vous_index', methods: ['GET','POST'])]
    public function index(RendezVousRepository $rendezVousRepository,Request $req,$nbre,$page): Response
    {
        $user=$this->getUser();
        $userId =$user->getUserIdentifier();
        $date = new DateTime('today');
        $todaysDate = $date->format('Y-m-d');
        $nbRDV = $rendezVousRepository->countUpcommingRDVs($todaysDate);
        $nbrePage = ceil($nbRDV / $nbre) ;
        $upcomingRDVs = $rendezVousRepository->findUpcomingRendezVouses($userId,$page,$nbre,$todaysDate);
        $todaysRDVs = $rendezVousRepository->findRendezVousesBydate($userId,$page,$nbre,$todaysDate);
        $form = $this->createForm(RendezVousRechercheType::class);
        $form->HandleRequest($req);
        
        if ($form -> isSUbmitted()) {
            $data=  $form->getData();
            $rech = $rendezVousRepository->rechercherRDV($data);
             return $this->render('Front-Office/rendez_vous/index2.html.twig', [
                 'rendez_vouses' => $rech,
                 'form'=>$form->createView(),
                 'user'=>$user
             ]);
         }
        return $this->render('Front-Office/rendez_vous/index.html.twig', [
            'rendez_vouses' => $upcomingRDVs,
            'today_rendez_vouses' =>$todaysRDVs ,
            'date'=> $todaysDate,
            'form'=>$form->createView(),
            'isPaginated'=>true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre,
            'user'=>$user

        ]);
    }
    #[Route('/admin/{page?1}/{nbre?5}', name: 'app_rendez_vous_admin_index', methods: ['GET']),IsGranted('ROLE_ADMIN')]
    public function indexAdmin(RendezVousRepository $rendezVousRepository,$page, $nbre): Response
    {
        $rendeVouses = $rendezVousRepository->findBy([], [],$nbre, ($page - 1 ) * $nbre);
        $nbRDVs = $rendezVousRepository->count([]);
        $nbrePage = ceil($nbRDVs / $nbre) ;
        return $this->render('Back-Office/rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendeVouses,
            'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
            

        ]);
    }

    /*#[Route('/{id}/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request,Planning $planning,UserRepository $patientRep ,RendezVousRepository $rendezVousRepository,HubInterface $hub): Response
    {
        $user=$this->getUser();
        $rendezVou = new RendezVous();
        $rendezVou->setPlanning($planning);
        $rendezVou->setPatient($patientRep->find(2));
        $rendezVou->setEtat("en attente");
        $date_creation = new DateTimeImmutable();
        $rendezVou->setDateDeCreation($date_creation);
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $rendezVousRepository->save($rendezVou, true);
            $data=[

                    'date' => $rendezVou->getDate()->format('Y-m-d'),
                    'heure'=>$rendezVou->getHeureDebut()->format('H:i:s'),
                    'idPatient'=>$rendezVou->getPatient()->getId(),
                    'nomPatient'=>$rendezVou->getPatient()->getNom().$rendezVou->getPatient()->getPrenom(),
                    'idMedecin'=>$rendezVou->getPlanning()->getMedecin()->getId()

                ]
            ;
            $update = new Update(
                '/rdvAjouter',  //.$rendezVou->getPlanning()->getMedecin()->getId(),
                json_encode($data)
            );
    
            $hub->publish($update);
            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/rendez_vous/new.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
            'user'=>$user
        ]);
    }*/

    #[Route('/{id}', name: 'app_rendez_vous_show', methods: ['GET'])]
    public function show(RendezVous $rendezVou): Response
    {
        $user=$this->getUser();
        return $this->render('Front-Office/rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
            'user'=>$user
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendez_vous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVousRepository->save($rendezVou, true);

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('Front-Office/rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form,
        ]);
        
    }
    
    #[Route('/{id}/confirm', name: 'app_rendez_vous_confirm', methods: ['GET', 'POST']),IsGranted('ROLE_MEDECIN')]
    public function confirm(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository,HubInterface $hub): Response
    {

        $rendezVou->setEtat("confirmé");
        $rendezVousRepository->save($rendezVou, true);
        $data=[

            'date' => $rendezVou->getDate()->format('Y-m-d'),
            'heure'=>$rendezVou->getHeureDebut()->format('H:i:s'),
            'idPatient'=>$rendezVou->getPatient()->getId(),
            'nomMedecin'=>$rendezVou->getPlanning()->getMedecin()->getNom().$rendezVou->getPlanning()->getMedecin()->getPrenom(),
            'idMedecin'=>$rendezVou->getPlanning()->getMedecin()->getId(),
            

        ];
            $update = new Update(
                '/rdvConfirme',  //.$rendezVou->getPlanning()->getMedecin()->getId(),
                json_encode($data)
            );

            $hub->publish($update);
        

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/cancel', name: 'app_rendez_vous_cancel', methods: ['GET', 'POST'])]
    public function cancel(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {

        $rendezVou->setEtat("annulé");
        $rendezVousRepository->save($rendezVou, true);
        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_rendez_vous_delete', methods: ['POST'])]
    public function delete(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->request->get('_token'))) {
            $rendezVousRepository->remove($rendezVou, true);
        }

        return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/recherche', name: 'app_rendez_vous_recherche', methods: ['GET' ,'POST'])]
    public function chercherRDV(RendezVousRepository $rendezVousRepository,Request $req): Response
    {
        $user=$this->getUser();
        $form = $this->createForm(RendezVousRechercheType::class);
        $form->HandleRequest($req);
        
        if ($form -> isSUbmitted()) {
            $data=  $form->getData();
            $rech = $rendezVousRepository->rechercherRDV($data);
             return $this->render('Front-Office/rendez_vous/index.html.twig', [
                 'rendez_vouses' => $rech,
             ]);
         }
         $result = $rendezVousRepository->findAll();
         return $this->render('Front-Office/rendez_vous/index.html.twig', [
            'rendez_vouses' => $result,
            'f'=>$form->createView(),
             'user'=>$user
        ]);
    }
}

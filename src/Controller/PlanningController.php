<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Entity\User;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

#[Route('/planning'),
    IsGranted ('IS_AUTHENTICATED_FULLY')
]
class PlanningController extends AbstractController
{
    #[Route('/show/{page?1}/{nbre?5}', name: 'app_planning_index', methods: ['GET'],),IsGranted('ROLE_MEDECIN')]
    public function index(PlanningRepository $planningRepository,$nbre,$page,UserRepository $repo): Response
    {
        $user=$this->getUser();
        $email=$user->getUserIdentifier();
        $user1=$repo->findUserByEmail($email);
        $userId=$user1->getId();
        $nbPlanning = $planningRepository->countPlanning();
        $nbrePage = ceil($nbPlanning / $nbre) ;
        return $this->render('Front-Office/planning/index.html.twig', [
            'plannings' => $planningRepository->findTous($email,$page,$nbre),
            'isPaginated'=>true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre,
            'user'=>$user1
        ]);
    }


    #[Route('/{id}/new', name: 'app_planning_new', methods: ['GET', 'POST']),IsGranted('ROLE_MEDECIN')]
    public function new($id ,Request $request, UserRepository $medecinRep,PlanningRepository $planningRepository): Response
    {
        $user=$this->getUser();
        $planning = new Planning();
        $planning ->setMedecin($medecinRep->find($id));
        $planning ->setEtat("en cours");
        $date_creation = new DateTimeImmutable();
        $planning->setDateDeCreation($date_creation);
        $planning->setDateDeModification($date_creation);
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
            'user'=>$user
        ]);
    }

    #[Route('/{id}', name: 'app_planning_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        $user=$this->getUser();
        return $this->render('Front-Office/planning/show.html.twig', [
            'planning' => $planning,
            'user'=>$user
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        $user=$this->getUser();
        $date_modification = new DateTimeImmutable();
        $planning->setDateDeModification($date_modification);
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
            'user'=>$user
        ]);
    }

    #[Route('/{id}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $planningRepository->remove($planning, true);
        }

        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }


}

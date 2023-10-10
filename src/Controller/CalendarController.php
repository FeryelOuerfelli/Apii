<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Form\RendezVousCalendarType;
use App\Repository\PlanningRepository;
use App\Repository\RendezVousRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

#[Route('/calendar'),
    IsGranted ('IS_AUTHENTICATED_FULLY')]
class CalendarController extends AbstractController
{
    #[Route('/{id_medecin}', name: 'app_calendar', methods: ['GET', 'POST'])]
    public function index($id_medecin,Request $request,PlanningRepository $planningRepository,UserRepository $patientRep,RendezVousRepository $rendezVousRepository): Response
    {
        $user=$this->getUser();
        $getRendezVous = $rendezVousRepository->findByMedecin($id_medecin);
        $rendeVouses = [];
        foreach($getRendezVous as $rendezVous){
            $datetimeStart = new DateTime($rendezVous->getDate()->format('Y-m-d ').$rendezVous->getHeureDebut()->format('H:i:s'));
            $datetimeEnd = new DateTime($rendezVous->getDate()->format('Y-m-d ').$rendezVous->getHeureFin()->format('H:i:s'));
            if($rendezVous->getEtat() == "confirmé"){
                $couleur = "rgb(130, 205, 71)";
            }else if($rendezVous->getEtat() == "annulé"){
                $couleur = "#e63c3c";
            }else{
                $couleur = "rgb(244, 164, 66)";
            }
            $rendeVouses[] = [
                'id'=> $rendezVous->getId(),
                'patientId'=>$rendezVous->getPatient()->getId(),
                'date'=>$rendezVous->getDate()->format('Y-m-d'),
                'heureDebut'=>$rendezVous->getHeureDebut()->format('H:i:s'),
                'heureFin'=>$rendezVous->getHeureFin()->format('H:i:s'),
                'start'=> $datetimeStart->format(DateTime::ATOM),
                'end'=> $datetimeEnd->format(DateTime::ATOM),
                'title'=> $rendezVous->getPatient()->getNom()." ".$rendezVous->getPatient()->getPrenom(),
                'type'=>'rendezVous',
                'overlap'=>true,
                'planningId'=> $rendezVous->getPlanning()->getId(),
               // 'allDay' => true,
                'symptomes'=> $rendezVous->getSymptomes(),
                'constraint'=>'availableForRDV',
                'color'=> $couleur
            ];
        }
        $getPlannings = $planningRepository->findByMedecin($id_medecin);
        foreach($getPlannings as $planning){
            $startDate =new \DateTime( $planning->getDateDebut()->format('Y-m-d ').$planning->getHeureDebut()->format('H:i:s'));
            $endDate = new \DateTime($planning->getDateFin()->format('Y-m-d ').$planning->getHeurefin()->format('H:i:s'));
            $startTime = $planning->getHeureDebut()->format('H:i:s');
            $endTime = $planning->getHeureFin()->format('H:i:s');


            $rendeVouses[] = [
                'id'=> $planning->getId(),
                'startTime'=>$startTime,
                'endTime'=>$endTime,
                'startRecur'=>$startDate->format('Y-m-d'),
                'endRecur'=>$endDate->modify('+1 day')->format('Y-m-d'),
                'description'=> $planning->getDescription(),
                'type'=>'planning',
                //'allDay' => true,
                'extendedProps' => [
                    'type' => 'recurring',
                ],
        
                'display'=> 'background',
                'groupId'=> 'availableForRDV',
            ];
    
            
            
        }

        $data = json_encode($rendeVouses);
        $rendezVou = new RendezVous();
        if(in_array('ROLE_PATIENT',$user->getRoles(),true)) {{
            return $this->render('Front-Office/calendar/index.html.twig', compact('data','user'));
        }}else{
            return $this->render('Front-Office/calendar/index2.html.twig', compact('data','user'));
        }
    }
    #[Route('/new/{planningId}', name: 'app_rendez_vous_calendar_new', methods: ['GET', 'POST']),IsGranted('ROLE_PATIENT')]
    public function new($planningId,Request $request,PlanningRepository $planningRep,UserRepository $patientRep ,RendezVousRepository $rendezVousRepository,HubInterface $hub,UserRepository $repo): Response
    {
        $user=$this->getUser();
        $userId=$repo->findUserByEmail($user->getUserIdentifier());
        $rendezVou = new RendezVous();
        $planning= $planningRep->find($planningId);
        $rendezVou->setPlanning($planning);
        $rendezVou->setPatient($patientRep->find($userId));
        $rendezVou->setEtat("en attente");
        $date_creation = new DateTimeImmutable();
        $rendezVou->setDateDeCreation($date_creation);
        $form = $this->createForm(RendezVousCalendarType::class, $rendezVou);
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
            '/rdvAjouter/'.$rendezVou->getPlanning()->getMedecin()->getId(),
            json_encode($data)
        );

        $hub->publish($update);

           return $this->redirectToRoute('app_calendar', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Front-Office/calendar/_form.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_calendar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RendezVous $rendezVou, RendezVousRepository $rendezVousRepository,PlanningRepository $planningRep,UserRepository $repo):Response
    {
        $user=$this->getUser();
        $userId=$repo->findUserByEmail($user->getUserIdentifier())->getId();
        $donnes = json_decode($request->getContent());
        $planning = $planningRep->find($donnes->planningId);
        if($userId==$donnes->patient_id or $userId==$planning->getMedecin()->getId()){

            $rendezVou->setPlanning($planningRep->find($donnes->planningId));
            $rendezVou->setDate(new DateTime($donnes->date));
            $rendezVou->setHeureDebut(new DateTime($donnes->heureDebut));
            $rendezVou->setHeureFin(new DateTime($donnes->heureFin));
            $rendezVou->setSymptomes($donnes->symptomes);
            $rendezVousRepository->save($rendezVou, true);

        }
        return new Response('success');

    }

}

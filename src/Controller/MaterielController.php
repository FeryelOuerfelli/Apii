<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Controller\UtilisateurController;
use App\Form\SearchFormType;
use App\Service\PDFService;
use App\Controller\SecurityController;

#[Route('/materiel'),
    IsGranted ('IS_AUTHENTICATED_FULLY')
]
class MaterielController extends AbstractController
{

#[Route('/listMateriels/{page?1}/{nbre?5}', name: 'list_materiels')]
public function list_materiel(MaterielRepository $materielRepository,Request $req, $page, $nbre): Response
{   
    $materiels = $materielRepository->findBy([], [],$nbre, ($page - 1 ) * $nbre);
    $nbmateriels = $materielRepository->count([]);
    $nbrePage = ceil($nbmateriels / $nbre) ;
    $form = $this->createForm(SearchFormType::class);
    $form->handleRequest($req);
    if($form->isSubmitted() ) {
        $searchTerm = $form->getData();
        $materiels = $materielRepository->findMaterielBySearchTerm($searchTerm);
    }
    return $this->render('Back-Office/materiel/index.html.twig', [
        'materiels' => $materiels,
        'form'=>$form->createView(),
        'isPaginated' => true,
        'nbrePage' => $nbrePage,
        'page' => $page,
        'nbre' => $nbre
        

    ]);
}


    #[Route('/new', name: 'app_materiel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MaterielRepository $materielRepository): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materielRepository->save($materiel, true);

            return $this->redirectToRoute('list_materiels', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Back-Office/materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_show', methods: ['GET'])]
    public function show(Materiel $materiel): Response
    {        $form = $this->createForm(MaterielType::class, $materiel);

        return $this->render('Back-Office/materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }
    
    #[Route('/emp/{id}', name: 'app_materiel_show_emp', methods: ['GET'])]
    public function show_materielemp(Materiel $materiel): Response
    {        $form = $this->createForm(MaterielType::class, $materiel);

        return $this->render('Front-Office/materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Materiel $materiel , ManagerRegistry $rg, Request $req,  SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($req);
      

        if ($form->isSubmitted() && $form->isValid()) {
            $imagem = $form->get('imagem')->getData();
            if ($imagem) {
                $originalFilename = pathinfo($imagem->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imagem->guessExtension();
                try {
                    $imagem->move(
                        $this->getParameter('utilisateurs_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $materiel->setImagem($newFilename);
            }

            $result = $rg->getManager();
            $result->persist($materiel);
            $result->flush();
        }




        return $this->renderForm('Back-Office/materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_delete', methods: ['POST'])]
    public function delete(Request $request, Materiel $materiel, MaterielRepository $materielRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materiel->getId(), $request->request->get('_token'))) {
            $materielRepository->remove($materiel, true);
        }

        return $this->redirectToRoute('list_materiels', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('emp/mat/{id}}', name: 'list_materiels_empper', methods: ['GET']),IsGranted('ROLE_EMPLOYE')]
public function materielAffecte(SecurityController $security)
{
    // Récupérez l'employé actuellement connecté
    $employe = $security->getUser();

    // Récupérez la liste du matériel affecté à cet employé
    $materiels = $employe->getMateriels();

    return $this->render('Front-Office/materiel/materiel_affecte.html.twig', [
        'employe' => $employe,
        'materiels' => $materiels,
    ]);
}
#[Route('emp/{page?1}/{nbre?5}', name: 'list_materiels_emp', methods: ['GET']),IsGranted('ROLE_EMPLOYE')]
public function list_materiel_emp(MaterielRepository $materielRepository,$page, $nbre): Response
{
    $materiels = $materielRepository->findBy([], [],$nbre, ($page - 1 ) * $nbre);
    $nbmateriels = $materielRepository->count([]);
    $nbrePage = ceil($nbmateriels / $nbre) ;
    return $this->render('Front-Office/materiel/index.html.twig', [
        'materiels' => $materiels,
        'isPaginated' => true,
        'nbrePage' => $nbrePage,
        'page' => $page,
        'nbre' => $nbre
        

    ]);
}

}

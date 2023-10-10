<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;
use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Entity\User;
use App\Form\UserType;
use App\Form\EditFormEmployeType;
use App\Repository\MaterielRepository;
use App\Service\PDFService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pdf')]
class PdfGeneratorController extends AbstractController
{
#[Route('/print/{id}', name: 'app_fact', methods: ['GET'])]

public function generatePdf(Materiel $materiel = null)
{   $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->setChroot (__DIR__);
    $pdfOptions->setIsRemoteEnabled(true);
    $dompdf = new Dompdf($pdfOptions);

    //$dompdf->set_option('isRemoteEnabled',TRUE);
    //$dompdf->set_option('isHtmlParserEnabled',TRUE);

 

    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);
    $dompdf->setHttpContext($context);

    $html = $this->render('Front-Office/materiel/showpdf1.html.twig',['materiel' => $materiel]) ;
    $dompdf->loadHtml($html);
    $dompdf->render();
    $fichier = 'materiel.pdf';
    $pdf = $dompdf->output();
     $dompdf->stream($fichier, [
        'Attachment' => true
    ]);
   // $fichier = $dompdf->output();
    //file_put_contents("file.pdf", $fichier);
    $tmpFile = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($tmpFile, $fichier);


   // $output = $dompdf->output();
//file_put_contents("file.pdf", $output);
  //  return new Response();



 return new Response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="document.pdf"',
    ]);
   
}
#[Route('/printfiche/{id}', name: 'app_fiche', methods: ['GET'])]

public function generatePdffiche(User $user = null)
{   $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->setChroot (__DIR__);
    $pdfOptions->setIsRemoteEnabled(true);
    $dompdf = new Dompdf($pdfOptions);

    //$dompdf->set_option('isRemoteEnabled',TRUE);
    //$dompdf->set_option('isHtmlParserEnabled',TRUE);

 

    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);
    $dompdf->setHttpContext($context);

    $html = $this->render('Back-Office/showpdf1.html.twig',['user' => $user]) ;
    $dompdf->loadHtml($html);
    $dompdf->render();
    $fichier = 'ficheemploye.pdf';
    $pdf = $dompdf->output();
     $dompdf->stream($fichier, [
        'Attachment' => true
    ]);
   // $fichier = $dompdf->output();
    //file_put_contents("file.pdf", $fichier);
    $tmpFile = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($tmpFile, $fichier);


   // $output = $dompdf->output();
//file_put_contents("file.pdf", $output);
  //  return new Response();



 return new Response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="document.pdf"',
    ]);
   
}
}

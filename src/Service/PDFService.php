<?php

namespace App\Service;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;

class PDFService
{
    private $dompdf ;

    public function generatePdf(Facture $facture = null)
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
    
        $html = $this->render('materiel/showpdf1.html.twig',['materiel' => $materiel]) ;
        $dompdf->loadHtml($html);
        $dompdf->render();
        $fichier = 'mat.pdf';
    
         $dompdf->stream($fichier, [
            'Attachment' => true
        ]);
        //$output = $dompdf->output();
        //file_put_contents("file.pdf", $output);
        $fichier = $dompdf->output();
        file_put_contents("file.pdf", $fichier);
    
        $tmpFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tmpFile, $fichier);
        unlink($tmpFile);
        $output = $dompdf->output();
       file_put_contents("file.pdf", $output);
        return new Response();
    
    
    
     return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);
       
    }
    }
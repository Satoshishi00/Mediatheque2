<?php


namespace App\Controller;

use App\Entity\Historique;
use App\Form\HistoriqueType;
use App\Repository\HistoriqueRepository;
use App\Service\BibliothequeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mailing")
 */
class MailingController extends AbstractController
{

    /**
     * @Route("/medias/non-retourne", name="historique_medias_non_retourne", methods={"GET"})
     */
    public function mediasNonRetourne(BibliothequeService $bibliothequeService)
    {
        return $this->render('mailing/send_mail.html.twig', [
            'results' => $bibliothequeService->notReturn($this->getDoctrine()->getRepository(Historique::class))
        ]);
    }


}

<?php

namespace App\Controller;

use App\Service\BibliothequeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Membre;
use App\Entity\Media;
use App\Entity\Historique;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $membres = $this->getDoctrine()->getRepository(Membre::class)->findAll();
        $medias = $this->getDoctrine()->getRepository(Media::class)->findAll();
        $historiques = $this->getDoctrine()->getRepository(Historique::class)->findAll();
        return $this->render('home/index.html.twig', [
            'membres' => $membres,
            'medias' => $medias,
            'historiques' => $historiques,
        ]);
    }

    /**
     * @Route("/test-non-rendu", name="tnr")
     */
    public function test(BibliothequeService $bibliothequeService)
    {
        //dd($bibliothequeService->notReturn($this->getDoctrine()->getRepository(Historique::class)));
        dd($bibliothequeService->notReturnSendMail($this->getDoctrine()));
    }

}


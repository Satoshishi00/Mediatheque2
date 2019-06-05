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
 * @Route("/historique")
 */
class HistoriqueController extends AbstractController
{
    /**
     * @Route("/", name="historique_index", methods={"GET"})
     */
    public function index(HistoriqueRepository $historiqueRepository): Response
    {
        return $this->render('historique/index.html.twig', [
            'historiques' => $historiqueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="historique_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $historique = new Historique();
        $form = $this->createForm(HistoriqueType::class, $historique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($historique);
            $entityManager->flush();

            return $this->redirectToRoute('historique_index');
        }

        return $this->render('historique/new.html.twig', [
            'historique' => $historique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="historique_show", methods={"GET"})
     */
    public function show(Historique $historique): Response
    {
        return $this->render('historique/show.html.twig', [
            'historique' => $historique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="historique_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Historique $historique): Response
    {
        $form = $this->createForm(HistoriqueType::class, $historique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('historique_index', [
                'id' => $historique->getId(),
            ]);
        }

        return $this->render('historique/edit.html.twig', [
            'historique' => $historique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="historique_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Historique $historique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$historique->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($historique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('historique_index');
    }

    /**
     * @Route("/medias/non-retourne", name="historique_medias_non_retourne", methods={"GET"})
     */
    public function mediasNonRetourne(BibliothequeService $bibliothequeService)
    {
        return $this->render('historique/non_rendu.html.twig', [
            'results' => $bibliothequeService->notReturn($this->getDoctrine()->getRepository(Historique::class))
        ]);
    }

    public function relance(BibliothequeService $bibliothequeService)
    {
        return $this->render('mail/send_mail.html.twig', [
            'results' => $bibliothequeService->notReturn($this->getDoctrine()->getRepository(Historique::class))
    ]);
    }


}

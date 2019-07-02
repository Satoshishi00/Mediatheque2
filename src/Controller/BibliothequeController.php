<?php

namespace App\Controller;

use App\Entity\Bibliotheque;
use App\Form\BibliothequeType;
use App\Repository\BibliothequeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bibliotheque")
 */
class BibliothequeController extends AbstractController
{
    /**
     * @Route("/", name="bibliotheque_index", methods={"GET"})
     */
    public function index(BibliothequeRepository $bibliothequeRepository): Response
    {
        return $this->render('bibliotheque/index.html.twig', [
            'bibliotheques' => $bibliothequeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bibliotheque_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bibliotheque = new Bibliotheque();
        $form = $this->createForm(BibliothequeType::class, $bibliotheque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bibliotheque);
            $entityManager->flush();

            return $this->redirectToRoute('bibliotheque_index');
        }

        return $this->render('bibliotheque/new.html.twig', [
            'bibliotheque' => $bibliotheque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bibliotheque_show", methods={"GET"})
     */
    public function show(Bibliotheque $bibliotheque): Response
    {
        return $this->render('bibliotheque/show.html.twig', [
            'bibliotheque' => $bibliotheque,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bibliotheque_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bibliotheque $bibliotheque): Response
    {
        $form = $this->createForm(BibliothequeType::class, $bibliotheque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bibliotheque_index', [
                'id' => $bibliotheque->getId(),
            ]);
        }

        return $this->render('bibliotheque/edit.html.twig', [
            'bibliotheque' => $bibliotheque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bibliotheque_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bibliotheque $bibliotheque): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bibliotheque->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bibliotheque);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bibliotheque_index');
    }
}

<?php

namespace App\Controller;

use App\Entity\Etagere;
use App\Form\EtagereType;
use App\Repository\EtagereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etagere")
 */
class EtagereController extends AbstractController
{
    /**
     * @Route("/", name="etagere_index", methods={"GET"})
     */
    public function index(EtagereRepository $etagereRepository): Response
    {
        return $this->render('etagere/index.html.twig', [
            'etageres' => $etagereRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="etagere_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $etagere = new Etagere();
        $form = $this->createForm(EtagereType::class, $etagere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($etagere);
            $entityManager->flush();

            return $this->redirectToRoute('etagere_index');
        }

        return $this->render('etagere/new.html.twig', [
            'etagere' => $etagere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etagere_show", methods={"GET"})
     */
    public function show(Etagere $etagere): Response
    {
        return $this->render('etagere/show.html.twig', [
            'etagere' => $etagere,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etagere_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etagere $etagere): Response
    {
        $form = $this->createForm(EtagereType::class, $etagere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('etagere_index', [
                'id' => $etagere->getId(),
            ]);
        }

        return $this->render('etagere/edit.html.twig', [
            'etagere' => $etagere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etagere_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Etagere $etagere): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etagere->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($etagere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('etagere_index');
    }
}

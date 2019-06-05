<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Form\MembreType;
use App\Form\SearchType;
use App\Repository\MembreRepository;
use App\Service\BibliothequeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/membre")
 */
class MembreController extends AbstractController
{
    /**
     * @Route("/", name="membre_index", methods={"GET"})
     */
    public function index(MembreRepository $membreRepository, Request $request, BibliothequeService $bibliothequeService): Response
    {
        return $this->render('membre/index.html.twig', [
            //'membres' => $membreRepository->findAll(),
            'paginator' => $bibliothequeService->paginator($membreRepository, $request->query->get('page', 1), 1)
        ]);
    }


    /**
     * @Route("/new", name="membre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $membre = new Membre();
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($membre);
            $entityManager->flush();

            return $this->redirectToRoute('membre_index');
        }

        return $this->render('membre/new.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="membre_search", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function search(Request $request){
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $results = [];
        if($form->isSubmitted() && $form->isValid()){
            $results = $this->getDoctrine()->getRepository(Membre::class)->search($form->getData());
        }
//         $search = $request->request->get('search-input', null);
//         $query = (!$search) ? [] : $this->getDoctrine()->getManager()->getRepository(Membre::class)->search($search);
        return $this->render('membre/search.html.twig', [
            'results' => $results,
            'form' => $form-> createView()
            ]);
    }

    /**
     * @Route("/{id}", name="membre_show", methods={"GET"})
     */
    public function show(Membre $membre): Response
    {
        return $this->render('membre/show.html.twig', [
            'membre' => $membre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="membre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Membre $membre): Response
    {
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('membre_index', [
                'id' => $membre->getId(),
            ]);
        }

        return $this->render('membre/edit.html.twig', [
            'membre' => $membre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="membre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Membre $membre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$membre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($membre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('membre_index');
    }

    
}

<?php

namespace App\Controller;

use App\Entity\Kudos;
use App\Form\KudosType;
use App\Repository\KudosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/kudos')]
final class KudosController extends AbstractController
{
    #[Route(name: 'app_kudos_index', methods: ['GET'])]
    public function index(KudosRepository $kudosRepository): Response
    {
        return $this->render('kudos/index.html.twig', [
            'kudos' => $kudosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_kudos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $kudo = new Kudos();
        $form = $this->createForm(KudosType::class, $kudo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($kudo);
            $entityManager->flush();

            return $this->redirectToRoute('app_kudos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('kudos/new.html.twig', [
            'kudo' => $kudo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_kudos_show', methods: ['GET'])]
    public function show(Kudos $kudo): Response
    {
        return $this->render('kudos/show.html.twig', [
            'kudo' => $kudo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_kudos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kudos $kudo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(KudosType::class, $kudo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_kudos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('kudos/edit.html.twig', [
            'kudo' => $kudo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_kudos_delete', methods: ['POST'])]
    public function delete(Request $request, Kudos $kudo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$kudo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($kudo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_kudos_index', [], Response::HTTP_SEE_OTHER);
    }
}

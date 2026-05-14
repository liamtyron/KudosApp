<?php

namespace App\Controller;

use App\Entity\Kudos;
use App\Entity\User;
use App\Form\KudosType;
use App\Repository\KudosRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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

    // #[Route('/new', name: 'app_kudos_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager, RequestStack $requestStack, UserRepository $userRepository): Response
    // {

    //     $session = $requestStack->getSession();
    //     if($request->isMethod('POST')){
    //     $name = $request->request->get('firstName');
    //     $user = $userRepository->findOneBy(['firstName' =>$name]);
    //     $surname = $request->request->get('lastName');
    //     $user = $userRepository->findOneBy(['laststName' =>$surname]);
    //     dd($name, $user);

    //    // $user = $userRepository->findOneBy('firstName' =>);

    //     $kudo = new Kudos();
    //     $form = $this->createForm(KudosType::class, $kudo);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($kudo);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_kudos_index', [], Response::HTTP_SEE_OTHER);
    //     }
    //     }
    //     return $this->render('kudos/new.html.twig', [
    //         'kudo' => $kudo,
    //         'form' => $form,
           
    //     ]);

       
    // }
     

    //     $session = $requestStack->getSession();
    //    if($request->isMethod('POST')){
    //     $email = $request->request->get('email');
    //     $user = $userRepository->findOneBy(['email' =>$email]);
    //     //dd($email, $user);

    //     if(!$user){
    //         $this->addFlash('error', 'No account linked to this email chile');
    //         return $this->redirectToRoute('app_forgot_pswd');
    //     }

    //     $session->set('reset_user_id', $user->getId());
    //     return $this->redirectToRoute('app_update_pswd');
    //    }
    //     return $this->render('password/email.html.twig');

        

    #[Route('/new', name: 'app_kudos_new')]
    public function new(Request $request, EntityManagerInterface $entityManager,): Response
    {
        $kudo = new Kudos();
        // 1. Create the form BEFORE the if() statement
        $form = $this->createForm(KudosType::class, $kudo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $kudo->setSender($this->getUser());

            $entityManager->persist($kudo);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        // 2. Return form for both GET and invalid POST
        return $this->render('kudos/new.html.twig', [
            'kudo' => $kudo,
            'form' => $form->createView(), // Pass createView()
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

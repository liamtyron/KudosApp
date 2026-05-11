<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    // #[Route('/new')]
    // public function new(Request $request, EntityManagerInterface $entityManager ): Response{
    //     $user = new User();
    //     $form =$this->createForm(UserType::class,$user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form ->isValid()){
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
            
    //     }


    // }

    
}

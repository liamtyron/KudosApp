<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\KudosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class KudosController extends AbstractController
{
    #[Route("/dashboard", name: 'app_dashboard')]
    public function kudos(KudosRepository $kudosRepository):Response
    {

    $kudos = $kudosRepository->findAll();

    return $this->render('kudos/kudos.html.twig', [
        'kudos' => $kudos,
    ]);

    //     $kudos = [
    //         [
    //             'sender' => [
    //                 'firstName' => 'Alice',
    //                 'lastName' => 'Smith',
    //                 'username' => 'alice123',
    //                 'profilePic' => 'alice.jpg',
    //             ],
    //             'receiver' => [
    //                 'firstName' => 'Bob',
    //                 'lastName' => 'Jones',
    //                 'username' => 'bobby_j',
    //                 'profilePic' => 'bob.jpg',
    //             ],
    //             'msgContent' => 'Bob crushed that client presentation today, incredible work!',
    //             'createdAt' => '2026-05-11 09:00:00',
    //         ],
    //         [
    //             'sender' => [
    //                 'firstName' => 'Carol',
    //                 'lastName' => 'White',
    //                 'username' => 'carol_w',
    //                 'profilePic' => 'carol.jpg',
    //             ],
    //             'receiver' => [
    //                 'firstName' => 'Alice',
    //                 'lastName' => 'Smith',
    //                 'username' => 'alice123',
    //                 'profilePic' => 'alice.jpg',
    //             ],
    //             'msgContent' => 'Alice helped me debug for 2 hours, absolute legend!',
    //             'createdAt' => '2026-05-11 10:30:00',
    //         ],
    //     ];

    //    $response = $this->render('kudos/kudos.html.twig', [
    //         'kudos' => $kudos,
    //    ]);

    //     return $response;
    }
}
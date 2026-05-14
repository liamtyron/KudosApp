<?php

namespace App\Controller;

use App\Repository\KudosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    
    #[Route("/dashboard", name: 'app_dashboard')]
    public function kudos(KudosRepository $kudosRepository):Response
    {

        

    $kudos = $kudosRepository->findAll();

    return $this->render('kudos/kudos.html.twig', [
        'kudos' => $kudos,
    ]);
    }


    #[Route('/search', name:'app_search')]
    public function search(Request $request, KudosRepository $kudosRepository):Response
    {
        $searchTerm = $request->query->get('q');
        $results = $searchTerm ? $kudosRepository->findByName($searchTerm): [];

    return $this->render('kudos/kudos.html.twig', [
        'results' => $results,
        'searchTerm' => $searchTerm,
    ]);
    
    }

}
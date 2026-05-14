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
         $query = $request->query->get('q');
    
   
    if ($query)
    {
        $kudos = $kudosRepository->findByName($query);
    } 
    else 
    {
        $kudos = $kudosRepository->findAll();
    
    }

    return $this->render('kudos/kudos.html.twig', [
        'kudos' => $kudos,
    ]);
    
    }


    #[Route('/sort', name:'app_sort_desc')]
    public function sortDesc(Request $request, KudosRepository $kudosRepository):Response
    {
       $order = $request->query->get('sort', 'DESC'); 
        $kudos = $kudosRepository->findBy([], ['createdAt' => $order]);
  
         return $this->render('kudos/kudos.html.twig', [
        'kudos' => $kudos,
        'currentOrder' => $order, 
        
        ]);
    }

 

    #[Route('/sort-it', name:'app_sort_asc')]
    public function sortAsc(Request $request, KudosRepository $kudosRepository): Response
    {
        $order = $request->query->get('sort', 'ASC'); 
            $kudos = $kudosRepository->findBy([], ['createdAt' => $order]);
    
            return $this->render('kudos/kudos.html.twig', [
            'kudos' => $kudos,
            'currentOrder' => $order, 
            
        ]);
    }


}
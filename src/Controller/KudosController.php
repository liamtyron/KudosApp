<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KudosController
{
    #[Route("/dashboard")]
    public function kudos():Response
    {
        $data = [
            [
                
            ],
        ];

        $content = "<html><body>Dashboard</body></html>";

        return new Response($content);
    }
}
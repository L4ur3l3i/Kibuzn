<?php

namespace Kibuzn\Controller\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class DashboardController extends AbstractController
{
    #[Route(name: 'app_dashboard_index', methods: ['GET'])]
    public function index(): Response
    {
        $hello = 'Hello, Kibuzn!';
        return $this->render('dashboard/index.html.twig', [
            'hello' => $hello,
        ]);
    }
}
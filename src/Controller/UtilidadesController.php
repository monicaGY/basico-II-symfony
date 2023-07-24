<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilidadesController extends AbstractController
{
    #[Route('/utilidades', name: 'utilidades_inicio')]
    public function index(): Response
    {
        return $this->render('utilidades/index.html.twig', [
            'controller_name' => 'UtilidadesController',
        ]);
    }
}

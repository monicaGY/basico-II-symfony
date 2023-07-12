<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormulariosController extends AbstractController
{
    #[Route('/formularios', name: 'form_inicio')]
    public function index(): Response
    {
        return $this->render('formularios/index.html.twig', [
            'controller_name' => 'FormulariosController',
        ]);
    }

    #[Route('/formularios/simple', name: 'form_simple')]
    public function simple(): Response
    {
        $formulario = $this->createFormBuilder(null)
            ->add('nombre', TextType::class, ['label' => 'Nombre'])
            ->add('correo', TextType::class, ['label' => 'Email'])
            ->add('telefono', TextType::class, ['label' => 'Telefono'])
            ->add('save', SubmitType::class,)
            ->getForm();
        return $this->render('formularios/simple.html.twig',compact('formulario'));
    }
}

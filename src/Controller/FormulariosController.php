<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Persona;

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
    public function simple(Request $request): Response
    {
        $formulario = $this->createFormBuilder(null)
            ->add('nombre', TextType::class, ['label' => 'Nombre'])
            ->add('correo', TextType::class, ['label' => 'Email'])
            ->add('telefono', TextType::class, ['label' => 'Telefono'])
            ->add('save', SubmitType::class,)
            ->getForm();

        

        $submiteddToken= $request->request->get('token');
        $formulario->handleRequest($request);
        if($formulario->isSubmitted())
        {
            if($this->isCsrfTokenValid('generico',$submiteddToken))
            {
                $campos = $formulario->getData();
                echo 'Nombre: '. $campos["nombre"];
                echo '<br>Correo: '. $campos["correo"];
                echo '<br>Teléfono: '. $campos["telefono"];
                die();
            }
            else
            {

                $this->addFlash('css','warning');
                $this->addFlash('mensaje','Ocurrió un error inesperado');
                return $this->redirectToRoute('form_simple');

            }
            
        }


        return $this->render('formularios/simple.html.twig',compact('formulario'));
    }


    #[Route('/formularios/entity', name: 'form_entity')]
    public function entity(Request $request): Response
    {
        $persona = new Persona();

        $formulario = $this->createFormBuilder($persona)
            ->add('nombre', TextType::class, ['label' => 'Nombre'])
            ->add('correo', TextType::class, ['label' => 'Email'])
            ->add('telefono', TextType::class, ['label' => 'Telefono'])
            ->add('save', SubmitType::class,)
            ->getForm();

        

        $submiteddToken= $request->request->get('token');
        $formulario->handleRequest($request);
        if($formulario->isSubmitted())
        {
            if($this->isCsrfTokenValid('generico',$submiteddToken))
            {
                $campos = $formulario->getData();
                echo 'Nombre: '. $campos->getNombre();
                echo '<br>Correo: '. $campos->getCorreo;
                echo '<br>Teléfono: '. $campos->getTelefono;
                die();
            }
            else
            {

                $this->addFlash('css','warning');
                $this->addFlash('mensaje','Ocurrió un error inesperado');
                return $this->redirectToRoute('form_entity');

            }
            
        }
        return $this->render('formularios/entity.html.twig',compact('formulario'));
    }
}

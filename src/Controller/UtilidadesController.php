<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//ENVIAR E-MAIL
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

//fallo en el envío de emails
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


//http client
use Symfony\Contracts\HttpClient\HttpClientInterface;

use Symfony\Component\HttpFoundation\Request;
use App\Form\PublicacionType;

use App\Form\AccionType;

class UtilidadesController extends AbstractController
{
    public function __construct(private HttpClientInterface $client,)
    {
    }

    #[Route('/utilidades', name: 'utilidades_inicio')]
    public function index(): Response
    {
        return $this->render('utilidades/index.html.twig');
    }

    #[Route('/utilidades/mail', name: 'utilidades_mail')]
    public function enviar_mail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('monicagarciayadaicela@gmail.com')
            ->to('isabel.migy@gmail.com')
            ->subject('Mi primer e-mail!')
            // el formato del texto del email puede ser text o htm
            ->text('Esto es una pruba de envíos de email con symfony!');
            // ->html('<p>Esto es una pruba de envíos de email con symfony!</p>');

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            die($e);
        }
        return $this->render('utilidades/mail.html.twig');
    }

    #[Route('/utilidades/api_rest', name: 'utilidades_api_rest')]
    public function api_rest(): Response
    {
        $response = $this->client->request(
            'GET',
            'https://jsonplaceholder.typicode.com/posts'

        );
        
        return $this->render('utilidades/api_rest.html.twig', compact('response'));
    }
    #[Route('/utilidades/api_rest/post', name: 'utilidades_api_rest_post')]
    public function api_rest_post(Request $request): Response
    {

        $form = $this->createForm(PublicacionType::class, null);
        $form -> handleRequest($request);
        $submitedToken = $request->request->get('token');

        if($form->isSubmitted()){
            if($this->isCsrfTokenValid('generico',$submitedToken)){

                $campos= $form->getData();
                $datos = [
                    'title' => $campos['title'],
                    'body' => $campos['body'],
                    'userId' => $campos['userId']
                ];

                if(empty($campos['title']) or empty($campos['body'])or empty($campos['userId'])){
                    $this->addFlash('css','danger');
                    $this->addFlash('respuesta', 'campos vacíos');
                    $this->addFlash('mensaje','rellena todos los campos');
                    return $this->redirectToRoute('utilidades_api_rest_post');

                }
                $response = $this->client->request(
                    'POST',
                    'https://jsonplaceholder.typicode.com/posts',
                    [
                        'json' => $datos
                    ]
                );

                $response = $response->getStatusCode();

                if($response === 201){
                    $this->addFlash('css','success');
                    $this->addFlash('respuesta',$response);
                    $this->addFlash('mensaje','proceso completado con éxito');
                }else{
                    $this->addFlash('css','danger');
                    $this->addFlash('respuesta',$response);
                    $this->addFlash('mensaje','vuelve a intentarlo más tarde');
                    return $this->redirectToRoute('utilidades_api_rest_post');
                }
                return $this->redirectToRoute('utilidades_api_rest_post');

            }
        }

        return $this->render('utilidades/api_rest_post.html.twig', compact('form'));
    }


    #[Route('/utilidades/api_rest_acciones', name: 'utilidades_api_rest_acciones')]
    public function api_rest_acciones(Request $request): Response
    {

        //1 - OBTENIENDO EL TOKEN
        $response = $this->client->request(
            'POST',
            'https://www.api.tamila.cl/api/login',
            [
                'json' => [
                    'correo' => 'info@tamila.cl',
                    'password' => 'p2gHNiENUw'
                ]
            ]

        );

        $responseJson = $response->getContent();
        $responseData = json_decode($responseJson, true, 512, JSON_THROW_ON_ERROR);

        $token = $responseData['token'];


        $response = $this->client->request(
            'GET',
            'https://www.api.tamila.cl/api/categorias',
            [
                'headers' => [
                    'Authorization' => 'Bearer '. $token
                ]
            ]

        );
        
        return $this->render('utilidades/api_rest_acciones.html.twig', compact('response'));
    }

    #[Route('/utilidades/api_rest_añadir', name: 'utilidades_api_rest_añadir')]
    public function añadir(Request $request): Response
    {
        $form = $this->createForm(AccionType::class, null);
        $form -> handleRequest($request);
        $submitedToken = $request->request->get('token');


        if($form->isSubmitted()){


            if($this->isCsrfTokenValid('generico',$submitedToken)){

                //1 - OBTENIENDO EL TOKEN
                $response = $this->client->request(
                    'POST',
                    'https://www.api.tamila.cl/api/login',
                    [
                        'json' => [
                            'correo' => 'info@tamila.cl',
                            'password' => 'p2gHNiENUw'
                        ]
                    ]
        
                );

                $responseJson = $response->getContent();
                $responseData = json_decode($responseJson, true, 512, JSON_THROW_ON_ERROR);
                $token = $responseData['token'];


                //2 - AÑADIR ELEMENTO
                $campos= $form->getData();
                $datos = [
                    'nombre' => $campos['nombre']
                ];

                $response = $this->client->request(
                    'POST',
                    'https://www.api.tamila.cl/api/categorias',
                    [
                        'headers' => [
                            'Authorization' => 'Bearer '.$token
                        ],
                        'json' => $datos
                    ]

                );

                $response = $response->getStatusCode();

                if($response === 201){
                    $this->addFlash('css','success');
                    $this->addFlash('respuesta',$response);
                    $this->addFlash('mensaje','proceso completado con éxito');
                }else{
                    $this->addFlash('css','danger');
                    $this->addFlash('respuesta',$response);
                    $this->addFlash('mensaje','vuelve a intentarlo más tarde');
                }
                return $this->redirectToRoute('utilidades_api_rest_añadir');

            }
        }

        return $this->render('utilidades/api_rest_añadir.html.twig', compact('form'));
    }
}

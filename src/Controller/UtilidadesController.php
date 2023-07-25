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
            'https://api.escuelajs.co/api/v1/categories'
        );
        
        return $this->render('utilidades/api_rest.html.twig', compact('response'));
    }
}

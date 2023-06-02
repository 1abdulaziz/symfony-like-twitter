<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(
        AuthenticationUtils $utils

    ): Response
    {
//        $transport = new EsmtpTransport('mailhog', 1025);
//        $mailer = new Mailer($transport);
//        $email = (new Email())
//            ->from('admin@spiritof2021.online')
//            ->to('kiusau@me.com')
//            ->subject('My first mail using Symfony Mailer')
//            ->text('This is an important message!')
//            ->html('This is an important message!');
//        try {
//            $mailer ->send($email);
//        } catch (Exception $e) {
//            echo "Caught exception: " . $e->getMessage();
//        }

        $lastUsername = $utils->getLastUsername();
        $error = $utils->getLastAuthenticationError();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
    }
}

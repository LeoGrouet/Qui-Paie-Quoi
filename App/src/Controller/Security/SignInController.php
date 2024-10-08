<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SignInController extends AbstractController
{
    #[Route('/signin', name: 'signin', methods: ['GET', 'POST'])]
    public function signup(
        AuthenticationUtils $authenticationUtils,
    ): Response {
        if ($this->isGranted('IS_AUTHENTICATED')) {
            return $this->redirectToRoute('groups_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('register/signin.html.twig', [
            'error' => $error,
        ]);
    }
}

<?php

namespace App\Controller\Security;

use App\Form\PasswordResetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Mail\ResetMail;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Transport\Smtp\Auth\LoginAuthenticator;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordController extends AbstractController
{

    #[Route('/forgot-password', name: 'forgot_password', methods: ['GET', 'POST'])]
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        SendEmailService $mail,
        JWTService $jwt
    ): Response {

        $form = $this->createForm(PasswordResetType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $userRepository->findOneByEmail($data['email']);

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            $payload = [
                'user_id' => $user->getId()
            ];

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwt_secret'));

            $mail->send(new ResetMail(
                'test.test@gmail.com',
                $user,
                $token
            ));
        }

        return $this->render(
            'register/forgotPassword.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    #[Route('/edit-password', name: 'edit_password', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
    ) {
        $form = $this->createForm(PasswordResetType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        return $this->render(
            'register/forgotPassword.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    #[Route('/reset-password/{token}', name: 'reset_password', methods: ['GET', 'POST'])]
    public function reset(
        Request $request,
        string $token,
        JWTService $jwt,
        UserRepository $usersRepository,
        Security $security,
        TranslatorInterface $translator
    ) {

        if ($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwt_secret'))) {
            $payload = $jwt->getPayload($token);

            if (!array_key_exists('user_id', $payload)) {

                $this->addFlash(
                    'notice',
                    $translator->trans('tokenError', [], 'authentification')
                );
                return $this->redirectToRoute('forgot_password');
            }

            $user = $usersRepository->findOneBy(['id' => $payload['user_id']]);

            $security->login($user);
        }

        $form = $this->createForm(PasswordResetType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }
        return $this->render(
            'register/forgotPassword.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}

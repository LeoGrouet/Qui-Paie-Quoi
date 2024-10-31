<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\MailPasswordResetType;
use App\Form\PasswordResetType;
use App\Form\UpdatePasswordFromLinkType;
use App\Mail\ResetMail;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'forgot_password', methods: ['GET', 'POST'])]
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        SendEmailService $mail,
        JWTService $jwt,
    ): Response {
        $form = $this->createForm(MailPasswordResetType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $userRepository->findOneByEmail($data['email']);

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256',
            ];

            $payload = [
                'user_id' => $user->getId(),
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
        Security $security,
        UserPasswordHasherInterface $passwordHasher,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManagerInterface,
    ) {
        $form = $this->createForm(PasswordResetType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $currentUser = $security->getUser();

            if (!$currentUser instanceof User) {
                $this->addFlash(
                    'notice',
                    $translator->trans('errorUser', [], 'authentification')
                );

                return $this->redirectToRoute('edit_password');
            }

            if ($passwordHasher->isPasswordValid($currentUser, $data['oldPassword']) && $data['oldPassword'] !== $data['newPassword']) {
                $currentUser->setPassword($passwordHasher->hashPassword($currentUser, $data['newPassword']));

                $entityManagerInterface->persist($currentUser);
                $entityManagerInterface->flush();

                $this->addFlash(
                    'success',
                    $translator->trans('passwordUpdateSuccess', [], 'authentification')
                );

                return $this->redirectToRoute('groups_home');
            }
        }

        return $this->render(
            'register/editPassword.html.twig',
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
        TranslatorInterface $translator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManagerInterface,
    ) {
        if (!$jwt->isValid($token) && $jwt->isExpired($token) && !$jwt->check($token, $this->getParameter('app.jwt_secret'))) {

            $this->addFlash(
                'notice',
                $translator->trans('tokenError', [], 'authentification')
            );

            return $this->redirectToRoute('forgot_password');
        }

        $payload = $jwt->getPayload($token);
        $user = $usersRepository->findOneBy(['id' => $payload['user_id']]);

        $form = $this->createForm(UpdatePasswordFromLinkType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user->setPassword($passwordHasher->hashPassword($user, $data['newPassword']));

            $this->addFlash(
                'success',
                $translator->trans('passwordUpdateSuccess', [], 'authentication')
            );

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('signin');
        }

        return $this->render(
            'register/resetPasswordFromLink.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}

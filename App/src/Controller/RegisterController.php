<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSignInType;
use App\Repository\UserRepository;
use Composer\Pcre\Regex;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/signin', name: 'signin', methods: ['GET'])]
class RegisterController extends AbstractController
{
    #[Route('/', name: '_home', methods: ['GET', 'POST'])]
    public function signIn(Request $request, EntityManagerInterface $entityManagerInterface, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserSignInType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (
                !is_array($data)
                || !array_key_exists('username', $data)
                || !array_key_exists('email', $data)
                || !array_key_exists('password', $data)
            ) {
                throw new \RuntimeException('Invalid data.');
            }

            if ($userRepository->findOneByname($data['username']) !== null) {
                $this->addFlash(
                    'notice',
                    'Ce nom d\'utilisateur est déjà utilisé.'
                );

                return $this->redirectToRoute('signin_home', [
                    'form' => $form,
                ]);
            }

            if ($userRepository->findOneByEmail($data['email']) !== null) {
                $this->addFlash(
                    'notice',
                    'Ce mail est déjà utilisé.'
                );

                return $this->redirectToRoute('signin_home', [
                    'form' => $form,
                ]);
            }

            if (!Regex::match('/"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"/', $data['password'])) {
                $this->addFlash(
                    'notice',
                    'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial .'
                );

                return $this->redirectToRoute('signin_home', [
                    'form' => $form,
                ]);
            }

            $user = new User(
                $data['username'],
                $data['email'],
                $data['password']
            );

            if ($data['password'] !== $data['passwordConfirm']) {
                $this->addFlash(
                    'notice',
                    'Les mots de passe ne correspondent pas.'
                );

                return $this->redirectToRoute('signin_home', [
                    'form' => $form,
                ]);
            }

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('groups_home');
        }

        return $this->render('register/signin.html.twig', [
            'form' => $form,
        ]);
    }
}

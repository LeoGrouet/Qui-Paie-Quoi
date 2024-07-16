<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSignUpType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/signup', name: 'signup', methods: ['GET', 'POST'])]
    public function signup(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
        $form = $this->createForm(UserSignUpType::class);

        $userSignUpDTO = $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            dump($userSignUpDTO->username);

            $user = new User(
                $data['username'],
                $data['email'],
            );
            $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

            $this->addFlash(
                'success',
                'Félicitations ! Votre compte a bien été créé.'
            );

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('register/signup.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        return $this->render('register/login.html.twig');
    }
}

<?php

namespace App\Controller;

use App\DTO\UserSignUpDTO;
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

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$data instanceof UserSignUpDTO) {
                $this->addFlash(
                    'notice',
                    'Une erreur est survenue. Veuillez réessayer.'
                );

                return $this->redirectToRoute('signup');
            }

            $user = new User(
                $data->getUsername(),
                $data->getEmail(),
            );
            $user->setPassword($passwordHasher->hashPassword($user, $data->getPassword()));

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

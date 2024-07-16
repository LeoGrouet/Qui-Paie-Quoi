<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSignInType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/signin', name: 'signin', methods: ['GET'])]
class RegisterController extends AbstractController
{
    #[Route('/', name: '_home', methods: ['GET', 'POST'])]
    public function signIn(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
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

            $user = new User(
                $data['username'],
                $data['email'],
            );
            $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('groups_home');
        }

        return $this->render('register/signin.html.twig', [
            'form' => $form,
        ]);
    }
}

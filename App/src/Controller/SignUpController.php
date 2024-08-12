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
use Symfony\Contracts\Translation\TranslatorInterface;

class SignUpController extends AbstractController
{
    #[Route('/signup', name: 'signup', methods: ['GET', 'POST'])]
    public function signup(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        UserPasswordHasherInterface $passwordHasher,
        TranslatorInterface $translator
    ): Response {
        if ($this->isGranted('IS_AUTHENTICATED')) {
            return $this->redirectToRoute('groups_home');
        }

        $form = $this->createForm(UserSignUpType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$data instanceof UserSignUpDTO) {
                $this->addFlash(
                    'notice',
                    $translator->trans('errorRegistration', [], 'authentication')
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
                $translator->trans('successRegistration', [], 'authentication')
            );

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('signin');
        }

        return $this->render('register/signup.html.twig', [
            'form' => $form,
        ]);
    }
}

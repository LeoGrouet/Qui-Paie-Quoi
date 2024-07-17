<?php

namespace App\Form;

use App\DTO\UserSignUpDTO;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserSignUpType extends AbstractType
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSignUpDTO::class,
            'empty_data' => function (FormInterface $form): UserSignUpDTO {
                return new UserSignUpDTO(
                    $form->get('username')->getData(),
                    $form->get('email')->getData(),
                    $form->get('password')->getData(),
                );
            },
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $callbackUsername = function (string $username, ExecutionContextInterface $context) {
            if ($this->userRepository->isUsernameAlreadyInUsed($username)) {
                $context
                    ->buildViolation("Ce nom d'utilisateur est déjà utilisé.")
                    ->addViolation();
            }
        };

        $callbackEmail = function (string $email, ExecutionContextInterface $context) {
            if ($this->userRepository->isEmailAlreadyInUsed($email)) {
                $context
                    ->buildViolation('Cet email est déjà utilisé.')
                    ->addViolation();
            }
        };

        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'Nom d\'utilisateur',
                    'attr' => [
                        'placeholder' => 'Nom Prénom',
                    ],
                    'required' => false,
                    'constraints' => [
                        new Callback($callbackUsername),
                    ],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Adresse email',
                    'attr' => [
                        'placeholder' => 'Saisir mon email',
                    ],
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un email.',
                        ]),
                        new Email([
                            'message' => 'Veuillez entrer un email.',
                        ]),
                        new Callback($callbackEmail),
                    ],
                ]
            )
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Mot de passe',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe.',
                        ]),
                        new PasswordStrength([
                            'minScore' => PasswordStrength::STRENGTH_WEAK,
                            'message' => 'Mot de passe trop faible (Essayer avec 3 caracteres différents et un chiffre)',
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmation du Mot de passe',
                    ],
                ],
            ])
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Je créer mon compte',
                ]
            );
    }
}

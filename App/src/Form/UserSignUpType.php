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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
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
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $callbackUsername = function (mixed $username, ExecutionContextInterface $context) {
            if (!is_string($username)) {
                return;
            }
            if ($this->userRepository->isUsernameAlreadyInUsed($username)) {
                $context
                    ->buildViolation("Ce nom d'utilisateur est déjà utilisé.")
                    ->addViolation();
            }
        };

        $callbackEmail = function (mixed $email, ExecutionContextInterface $context) {
            if (!is_string($email)) {
                return;
            }
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
                        'placeholder' => 'Nom d\'utilisateur',
                    ],
                    'required' => true,
                    'constraints' => [
                        new NotNull(
                            message: 'Veuillez entrer un nom d\'utilisateur .',
                        ),
                        new Length(
                            min: 4,
                            minMessage: 'Nom d\'utilisateur trop court ( Minimum 4 caractères) .',
                            max: 255,
                            maxMessage: 'Nom d\'utilisateur trop long ( Max 255 caractères) .',
                        ),
                        new NotBlank(
                            message: 'Veuillez entrer un nom d\'utilisateur.',
                            normalizer: 'trim',
                        ),
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
                        new NotNull(
                            message: 'Veuillez entrer un email.',
                        ),
                        new NotBlank(
                            message: 'Veuillez entrer un email.',
                        ),
                        new Length(
                            max: 255,
                            maxMessage: 'Votre email est trop long ( Max 255 caractères) .',
                        ),
                        new Email(
                            message: 'Veuillez entrer un email valide.',
                        ),
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
                        new NotNull(
                            message: 'Veuillez entrer un mot de passe.',
                        ),
                        new NotBlank(
                            message: 'Veuillez entrer un mot de passe.',
                        ),
                        new PasswordStrength(
                            minScore: PasswordStrength::STRENGTH_WEAK,
                            message: 'Mot de passe trop faible (Au moins 1 majuscule, minuscule, chiffre et caractère spéciaux ).',
                        ),
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

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
use Symfony\Contracts\Translation\TranslatorInterface;

class UserSignUpType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserSignUpDTO::class,
            'trans_domain' => 'authentication',
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
                    ->buildViolation('usernameAllreadyUsed')
                    ->setTranslationDomain('authentication')
                    ->addViolation();
            }
        };

        $callbackEmail = function (mixed $email, ExecutionContextInterface $context) {
            if (!is_string($email)) {
                return;
            }
            if ($this->userRepository->isEmailAlreadyInUsed($email)) {
                $context
                    ->buildViolation('emailAllreadyUsed')
                    ->setTranslationDomain('authentication')
                    ->addViolation();
            }
        };

        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'username',
                    'attr' => [
                        'placeholder' => 'username',
                    ],
                    'translation_domain' => $options['trans_domain'],
                    'required' => true,
                    'constraints' => [
                        new NotNull([
                            'message' => $this->translator->trans('usernameRequired', [], 'authentication'),
                        ]),
                        new Length([
                            'min' => 4,
                            'minMessage' => $this->translator->trans('usernameTooShort', [], 'authentication'),
                            'max' => 64,
                            'maxMessage' => $this->translator->trans('usernameTooLong', [], 'authentication'),
                        ]),
                        new NotBlank([
                            'normalizer' => 'trim',
                            'message' => $this->translator->trans('usernameRequired', [], 'authentication'),
                        ]),
                        new Callback($callbackUsername),
                    ],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'emailLabel',
                    'attr' => [
                        'placeholder' => 'emailPlaceholder',
                    ],
                    'required' => true,
                    'constraints' => [
                        new NotNull([
                            'message' => $this->translator->trans('emailRequired', [], 'authentication'),
                        ]),
                        new NotBlank([
                            'message' => $this->translator->trans('emailRequired', [], 'authentication'),
                        ]),
                        new Length(
                            [
                                'max' => 320,
                                'maxMessage' => $this->translator->trans('emailTooLong', [], 'authentication'),
                            ]
                        ),
                        new Email([
                            'message' => $this->translator->trans('emailValid', [], 'authentication'),
                        ]),
                        new Callback($callbackEmail),
                    ],
                    'translation_domain' => $options['trans_domain'],
                ]
            )
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'password',
                    'attr' => [
                        'placeholder' => 'password',
                    ],
                    'translation_domain' => $options['trans_domain'],
                    'constraints' => [
                        new NotNull([
                            'message' => $this->translator->trans('passwordRequired', [], 'authentication'),
                        ]),
                        new NotBlank([
                            'message' => $this->translator->trans('passwordRequired', [], 'authentication'),
                        ]),
                        new PasswordStrength(
                            minScore: PasswordStrength::STRENGTH_WEAK,
                            message: $this->translator->trans('passwordWeak', [], 'authentication'),
                        ),
                    ],
                ],
                'second_options' => [
                    'label' => 'confirmPassword',
                    'attr' => [
                        'placeholder' => 'confirmPassword',
                    ],
                    'translation_domain' => $options['trans_domain'],
                ],
            ])
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'signUpFormButton',
                    'translation_domain' => $options['trans_domain'],
                ]
            );
    }
}

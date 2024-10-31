<?php

namespace App\Form;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Contracts\Translation\TranslatorInterface;

class UpdatePasswordFromLinkType extends AbstractType
{
    public function __construct(
        private readonly Security $security,
        private readonly RequestStack $requestStack,
        private readonly TranslatorInterface $translator,
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'trans_domain' => 'authentication',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'newPassword',
                    'attr' => [
                        'placeholder' => 'newPassword',
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
                    'label' => 'confirmNewPassword',
                    'attr' => [
                        'placeholder' => 'confirmNewPassword',
                    ],
                    'translation_domain' => $options['trans_domain'],
                ],
            ])
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => $this->translator->trans('resetPassword', [], 'authentication'),
                    'translation_domain' => $options['trans_domain'],
                ]
            )
        ;
    }
}

<?php

namespace App\Form;

use App\DTO\PasswordUpdateDTO;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordResetType extends AbstractType
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
                        new Email([
                            'message' => $this->translator->trans('emailValid', [], 'authentication'),
                        ]),
                    ],
                    'translation_domain' => $options['trans_domain'],
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => $this->translator->trans('send', [], 'authentication'),
                    'translation_domain' => $options['trans_domain'],
                ]
            )
        ;
    }
}

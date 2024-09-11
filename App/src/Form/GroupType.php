<?php

namespace App\Form;

use App\DTO\GroupDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class GroupType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupDTO::class,
            'trans_domain' => 'groups',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'nameGroup',
                    'attr' => [
                        'placeholder' => 'groupPlaceholder',
                    ],
                    'translation_domain' => $options['trans_domain'],
                    'required' => true,
                    'constraints' => [
                        new NotNull(),
                        new Length(
                            min: 5,
                            max: 60,
                        ),
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'descriptionGroup',
                    'attr' => [
                        'placeholder' => 'descriptionPlaceholder',
                    ],
                    'translation_domain' => $options['trans_domain'],
                    'required' => true,
                    'constraints' => [
                        new NotNull(),
                        new Length(
                            max: 180,
                        ),
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'users',
                EntityType::class,
                [
                    'class' => User::class,
                    'choices' => $this->userRepository->findAll(),
                    'choice_label' => 'username',
                    'multiple' => true,
                    'expanded' => true,
                    'attr' => [
                        'size' => 5,
                    ],
                    'label' => 'addGroupParticipant',
                    'translation_domain' => $options['trans_domain'],
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'addGroupSubmitButton',
                    'translation_domain' => $options['trans_domain'],
                ]
            );
    }
}

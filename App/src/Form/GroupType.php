<?php

namespace App\Form;

use App\DTO\GroupDTO;
use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class GroupType extends AbstractType
{
    public function __construct(
        private readonly GroupRepository $groupRepository,
        private readonly UserRepository $userRepository,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupDTO::class
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'name'
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'description'
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
                    'label' => 'Participants',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => "Créer un groupe"
                ]
            );
    }
}

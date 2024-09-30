<?php

namespace App\Form;

use App\DTO\ExpenseDTO;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;

class ExpenseType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Security $security
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExpenseDTO::class,
            'trans_domain' => 'addExpense',
        ]);

        $resolver->setDefined('group');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $group = $options['group'];
        if (!$group instanceof Group) {
            throw new \Exception('Group is not defined');
        }
        if (!$this->security->getUser() instanceof UserInterface) {
            throw new \Exception('User is not authenticated');
        }
        $user = $this->userRepository->findOneByEmail($this->security->getUser()->getUserIdentifier());

        $builder
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'descriptionExpense',
                    'attr' => [
                        'placeholder' => 'expensePlaceholder',
                    ],
                    'translation_domain' => $options['trans_domain'],
                    'required' => true,
                ]
            )
            ->add(
                'amount',
                IntegerType::class,
                [
                    'label' => 'amountExpense',
                    'attr' => [
                        'placeholder' => 'amountPlaceholder',
                    ],
                    'translation_domain' => $options['trans_domain'],
                    'required' => true,
                ]
            )
            ->add(
                'payer',
                EntityType::class,
                [
                    'class' => User::class,
                    'choices' => $group->getUsers(),
                    'choice_label' => 'username',
                    'data' => $user,
                    'label' => 'payerExpense',
                    'translation_domain' => $options['trans_domain'],
                    'required' => true,
                ]
            )
            ->add(
                'participants',
                EntityType::class,
                [
                    'class' => User::class,
                    'choices' => $group->getUsers(),
                    'choice_label' => 'username',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'particicipantsPlaceholder',
                    'attr' => [
                        'placeholder' => 'particicipantsPlaceholder',
                    ],
                    'translation_domain' => $options['trans_domain'],
                    'required' => true,
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'addExpenseSubmitButton',
                    'translation_domain' => $options['trans_domain'],
                ]
            );
    }
}

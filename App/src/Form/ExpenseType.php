<?php

namespace App\Form;

use App\DTO\ExpenseDTO;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ExpenseType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Security $security,
        private readonly RequestStack $requestStack
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExpenseDTO::class,
            'user' => null,
            'trans_domain' => 'addExpense',
        ]);

        $resolver->setDefined('group');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $currentRoute = $request->attributes->get('_route');

        $label = $currentRoute === 'update_expense' ? 'updateLabel' : 'addExpenseSubmitButton';

        $payer = $options['user'];

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
                MoneyType::class,
                [
                    'html5' => true,
                    'label' => 'amountExpense',
                    'currency' => 'EUR',
                    'divisor' => 100,
                    'attr' => [
                        'placeholder' => 'amountPlaceholder',
                        'step' => '0.01',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Type([
                            'type' => 'numeric',
                            'message' => 'Please enter a valid number.',
                        ]),
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
                    'data' => $payer,
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
                    'label' => $label,
                    'translation_domain' => $options['trans_domain'],
                ]
            );
    }
}

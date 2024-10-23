<?php

namespace App\Form;

use App\DTO\GroupDTO\CreateGroupDTO;
use App\DTO\GroupDTO\UpdateGroupDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\Translation\TranslatorInterface;

class GroupType extends AbstractType
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RequestStack $requestStack,
        private readonly TranslatorInterface $translator,
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new \Exception('Request is not defined');
        }

        $currentMethod = $request->getMethod();

        if (Request::METHOD_POST === $currentMethod) {
            $dto = UpdateGroupDTO::class;
        } else {
            $dto = CreateGroupDTO::class;
        }

        $resolver->setDefaults([
            'data_class' => UpdateGroupDTO::class,
            'trans_domain' => 'groups',
        ]);

        $resolver->setDefined('users');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new \Exception('Request is not defined');
        }
        $currentRoute = $request->attributes->get('_route');

        if ('update_group' === $currentRoute) {
            $label = 'editGroupTitle';
            $method = Request::METHOD_PUT;
        } else {
            $label = 'createGroupTitle';
            $method = Request::METHOD_POST;
        }

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
                    'label' => $label,
                    'translation_domain' => $options['trans_domain'],
                ]
            )
            ->setMethod($method);
    }
}

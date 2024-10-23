<?php

namespace App\Controller\Group;

use App\DTO\GroupDTO;
use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/groups', name: 'groups_', methods: Request::METHOD_GET)]
class PageController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'home', methods: Request::METHOD_GET)]
    public function showGroups(
        GroupRepository $groupRepository,
        #[CurrentUser] User $user,
    ): Response {
        $groups = $groupRepository->findAllWhereUserIsMember($user);

        return $this->render(
            'group/groups.html.twig',
            [
                'user' => $user,
                'groups' => $groups,
            ]
        );
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function addGroup(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        TranslatorInterface $translator,
    ): Response {
        $form = $this->createForm(GroupType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$data instanceof GroupDTO) {
                $this->addFlash(
                    'notice',
                    $translator->trans('errorGroup', [], 'groups')
                );

                return $this->redirectToRoute('groups_add');
            }

            $group = new Group(
                $data->getName(),
                $data->getDescription()
            );

            if (null !== $data->getUsers()) {
                $group->setUsers($data->getUsers());
            }

            $this->addFlash(
                'success',
                $translator->trans('succesGroup', [], 'groups')
            );

            $entityManagerInterface->persist($group);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('groups_home');
        }

        return $this->render(
            'group/addGroup.html.twig',
            [
                'form' => $form,
            ]
        );
    }
}

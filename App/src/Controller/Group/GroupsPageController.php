<?php

namespace App\Controller\Group;

use App\DTO\GroupDTO;
use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/groups', name: 'groups_', methods: ['GET'])]
class GroupsPageController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function showGroups(
        GroupRepository $groupRepository,
        Security $security
    ): Response {
        $user = $security->getUser();

        $groups = $groupRepository->findAll();

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
    ): Response {

        $form = $this->createForm(GroupType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$data instanceof GroupDTO) {
                $this->addFlash(
                    'notice',
                    "Erreur groupe page controller"
                );

                return $this->redirectToRoute('groups_add');
            }

            $group = new Group(
                $data->getName(),
                $data->getDescription(),
                $data->getUsers()
            );

            $this->addFlash(
                'success',
                "Nouveau groupe ajouté"
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

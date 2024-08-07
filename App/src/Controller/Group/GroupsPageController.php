<?php

namespace App\Controller\Group;

use App\Form\AddGroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GroupsPageController extends AbstractController
{
    #[Route('/groups', name: 'groups_home', methods: ['GET'])]
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

    #[Route('/groups/add', name: 'groups_add', methods: ['GET', 'POST'])]
    public function addGroup(
        Request $request,
    ): Response {
        $form = $this->createForm(AddGroupType::class);

        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $data = $form->getData();

        //     $this->addFlash('success', 'Nouveau groupe créé');

        //     return $this->redirectToRoute('groups_home');
        // }

        return $this->render(
            'group/addGroup.html.twig',
            [
                'form' => $form,
            ]

        );
    }
}

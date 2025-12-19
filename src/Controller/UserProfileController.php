<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[IsGranted('ROLE_USER')]
final class UserProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    // afficher le profile
    public function show(): Response
    {
        $LeUserActuel = $this->getUser();

        return $this->render('user_profile/show.html.twig', [
            'user' => $LeUserActuel,
        ]);
    }

    #[Route('/user/profile/edit', name: 'app_user_profile_edit')]
    public function edit(Request $TheRequest, EntityManagerInterface $Manager, UserPasswordHasherInterface $HashMe): Response
    {
        /** @var User $user */
        $UserToEdit = $this->getUser();

        $EditForm = $this->createForm(UserProfileFormType::class, $UserToEdit);
        $EditForm->handleRequest($TheRequest);

        if ($EditForm->isSubmitted() && $EditForm->isValid()) {
            // Si un nouveau mot de passe est fourni, le hasher
            $PasswordNew = $EditForm->get('plainPassword')->getData();
            if ($PasswordNew) {
                // atention au hashage
                $UserToEdit->setPassword($HashMe->hashPassword($UserToEdit, $PasswordNew));
            }

            $Manager->flush();

            // c bon
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');

            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('user_profile/edit.html.twig', [
            'user' => $UserToEdit,
            'form' => $EditForm,
        ]);
    }

    #[Route('/user/profile/delete', name: 'app_user_profile_delete', methods: ['POST'])]
    public function delete(Request $LaRequete, EntityManagerInterface $MonEntityManager, TokenStorageInterface $LeTokenStorage): Response
    {
        /** @var User $user */
        $UserToDelete = $this->getUser();

        if ($this->isCsrfTokenValid('delete' . $UserToDelete->getId(), $LaRequete->getPayload()->getString('_token'))) {
            // Important: Clear the token and session FIRST to prevent "refresh user" errors
            // because the user ID will be nullified after flush.
            $LeTokenStorage->setToken(null);
            $LaRequete->getSession()->invalidate();

            // on suprime le user
            $MonEntityManager->remove($UserToDelete);
            $MonEntityManager->flush();
        }

        return $this->redirectToRoute('app_home');
    }
}

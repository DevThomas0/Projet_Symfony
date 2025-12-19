<?php

namespace App\Controller;

use App\Entity\PasswordResetToken;
use App\Entity\User;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\PasswordResetTokenRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ResetPasswordController extends AbstractController
{
    #[Route('/mot-de-passe-oublie', name: 'app_forgot_password')]
    // demande de reset
    public function request(
        Request $Requete,
        UserRepository $RepoUser,
        PasswordResetTokenRepository $RepoToken
    ): Response {
        $LeFormulaire = $this->createForm(ForgotPasswordFormType::class);
        $LeFormulaire->handleRequest($Requete);

        if ($LeFormulaire->isSubmitted() && $LeFormulaire->isValid()) {
            $EmailAddress = $LeFormulaire->get('email')->getData();

            /** @var User|null $user */
            // on cherche le user
            $Lutilisateur = $RepoUser->findOneBy(['email' => $EmailAddress]);

            if ($Lutilisateur) {
                // on met une heure d'expiration (c importan)
                $DateExpiration = (new \DateTimeImmutable())->modify('+1 hour');
                $LeToken = $RepoToken->createTokenForUser($Lutilisateur, $DateExpiration);

                // Dans un vrai projet, on enverrait ici un email contenant le lien.
                // Pour ce projet, on affiche le lien dans un message flash pour simplifier.
                $ResetUrlGenerated = $this->generateUrl('app_reset_password', ['token' => $LeToken->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
                $this->addFlash('info', sprintf('Lien de réinitialisation (à titre de démonstration) : %s', $ResetUrlGenerated));
            }

            $this->addFlash('success', 'Si un compte existe avec cet email, un lien de réinitialisation a été généré.');

            return $this->redirectToRoute('app_forgot_password');
        }

        return $this->render('security/forgot_password.html.twig', [
            'requestForm' => $LeFormulaire->createView(),
        ]);
    }

    #[Route('/reinitialiser-mot-de-passe/{token}', name: 'app_reset_password')]
    public function reset(
        string $token,
        Request $LaRequest,
        PasswordResetTokenRepository $RepoDesTokens,
        EntityManagerInterface $ManagerEntity,
        UserPasswordHasherInterface $HasherPass
    ): Response {
        /** @var PasswordResetToken|null $resetToken */
        // on verifie le token
        $TokenReset = $RepoDesTokens->findOneBy(['token' => $token]);

        if (!$TokenReset || $TokenReset->isExpired()) {
            $this->addFlash('danger', 'Ce lien de réinitialisation est invalide ou expiré.');

            return $this->redirectToRoute('app_forgot_password');
        }

        $FormulaireReset = $this->createForm(ResetPasswordFormType::class);
        $FormulaireReset->handleRequest($LaRequest);

        if ($FormulaireReset->isSubmitted() && $FormulaireReset->isValid()) {
            /** @var User $user */
            $UserToReset = $TokenReset->getUser();
            $PasswordClair = $FormulaireReset->get('plainPassword')->getData();

            // on change le mdp
            $UserToReset->setPassword($HasherPass->hashPassword($UserToReset, $PasswordClair));
            $ManagerEntity->flush();

            // on suprime le token
            $RepoDesTokens->removeToken($TokenReset);

            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'resetForm' => $FormulaireReset->createView(),
        ]);
    }
}



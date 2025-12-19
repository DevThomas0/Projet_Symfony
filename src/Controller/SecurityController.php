<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $UtilsDauthentification): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // recupere l'erreur de connexion s'il y en a une
        $Lerreur = $UtilsDauthentification->getLastAuthenticationError();
        // dernier nom d'utilisateur entré par le user
        $DernierUsername = $UtilsDauthentification->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $DernierUsername, 'error' => $Lerreur]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

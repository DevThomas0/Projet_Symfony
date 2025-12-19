<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Security\LoginAuthAuthenticator;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    // fonction d'enregistrement
    public function register(Request $LaRequest, UserPasswordHasherInterface $HasherDePassword, EntityManagerInterface $ManagerOfEntity, UserAuthenticatorInterface $AuthentificatorUser, LoginAuthAuthenticator $TheAuthenticator): Response
    {
        // nouvel utilisateur
        $LeUser = new User();
        $RegistrationForm = $this->createForm(RegistrationFormType::class, $LeUser);
        $RegistrationForm->handleRequest($LaRequest);

        // on verifie si c'est valide
        if ($RegistrationForm->isSubmitted() && $RegistrationForm->isValid()) {
            /** @var string $plainPassword */
            $PasswordClair = $RegistrationForm->get('plainPassword')->getData();

            // encode the plain password
            // on hash le mdp
            $LeUser->setPassword($HasherDePassword->hashPassword($LeUser, $PasswordClair));

            $ManagerOfEntity->persist($LeUser);
            $ManagerOfEntity->flush();

            // faite ce que vous voulez ici, comme envoyer un email


            // return $this->redirectToRoute('app_home');
            // on connecte automatiquement
            return $AuthentificatorUser->authenticateUser(
                $LeUser,
                $TheAuthenticator,
                $LaRequest
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $RegistrationForm,
        ]);
    }
}

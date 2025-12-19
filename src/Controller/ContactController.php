<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    // C'est la fonction index pour montrer la page de contact
    public function index(Request $LaRequest, EntityManagerInterface $MonManagerEntity): Response
    {
        // On créé le nouveau contact ici
        $LeContact = new Contact();

        // Creation of the form using the type
        $LeFormulaire = $this->createForm(ContactFormType::class, $LeContact);

        $LeFormulaire->handleRequest($LaRequest);

        // si le formulaire est bon, on l'envois a la base
        if ($LeFormulaire->isSubmitted() && $LeFormulaire->isValid()) {

            $MonManagerEntity->persist($LeContact);
            $MonManagerEntity->flush();

            // We add a flash message for the user
            $this->addFlash('success', 'Votre message a été envoyé avec succès.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $LeFormulaire,
        ]);
    }
}

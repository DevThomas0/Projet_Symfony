<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgotPasswordFormType extends AbstractType
{
    // Construire le formulaire pour l'oublie de mot de passe
    public function buildForm(FormBuilderInterface $LeMainBuilder, array $DesOptions): void
    {
        // ajout des champs
        $LeMainBuilder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'constraints' => [
                    // on verifi si c'est vide
                    new NotBlank(message: 'Veuillez entrer votre adresse email'),
                    new Email(message: 'Veuillez entrer une adresse email valide'),
                ],
            ])
        ;
    }

    // Configurer les options du resolveur
    public function configureOptions(OptionsResolver $MonResolver): void
    {
        $MonResolver->setDefaults([]);
    }
}



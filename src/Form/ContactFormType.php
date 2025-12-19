<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    // Fonction pour constuire le formulair
    public function buildForm(FormBuilderInterface $MonBuilder, array $YesOptions): void
    {
        //ici on ajoute les diferents champs, c'est facile
        $MonBuilder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer votre nom'),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    // We check if the email is not blank
                    new NotBlank(message: 'Veuillez entrer votre email'),
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet',
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un sujet'),
                ],
            ])
            // Pour le message du user
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['rows' => 10],
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un message'),
                ],
            ])
        ;
    }

    // configuration of the options for the resolution
    public function configureOptions(OptionsResolver $LeResolver): void
    {
        // on definit la class de donné par defaut (atention aux fotes)
        $LeResolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

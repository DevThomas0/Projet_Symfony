<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    // On construit le formulaire de service
    public function buildForm(FormBuilderInterface $BuilderDuForm, array $OptionsDuForm): void
    {
        // c'est ici qu'on met les champs
        $BuilderDuForm
            ->add('name', TextType::class, [
                'label' => 'Nom du service',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['rows' => 5],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'EUR',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $Resolvotron): void
    {
        $Resolvotron->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}

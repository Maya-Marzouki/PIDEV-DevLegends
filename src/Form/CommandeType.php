<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomClient')
            ->add('adresseEmail')
            ->add('dateCommande', DateType::class, [
                'widget' => 'single_text',  // Affichage d'un champ de type date
                'required' => true,         // Le champ est obligatoire
                'data' => $options['data']->getDateCommande() ?? new \DateTime(),  // Utiliser la date actuelle si non définie
            ])
    
            ->add('adresse')
            ->add('modePaiement', ChoiceType::class, [
                'choices' => [
                    'Carte Bancaire' => 'carte',
                    'Virement Bancaire' => 'virement',
                    'PayPal' => 'paypal',
                ],
                'mapped' => false,  // Ne pas lier ce champ à l'entité Commande
                'expanded' => true, // Affichage en boutons radio
                'multiple' => false,
            ])
            
            ->add('numeroCarte', TextType::class, [
                'mapped' => false,  // Champ non lié à l'entité
                'required' => false,  // Facultatif
            ])
            
            ->add('numeroVirement', TextType::class, [
                'mapped' => false,
                'required' => false,
            ])
            
            ->add('paypalEmail', TextType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('pays', TextType::class, [
                'data' => 'Tunisie', // Définir "Tunisie" comme valeur par défaut
                'disabled' => true,  // Empêcher la modification du pays
                'required' => true,
            ])
            
            
            ->add('NumTelephone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => true,
            ]);
         
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}

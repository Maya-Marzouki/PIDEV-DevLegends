<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Formation;  // Ajout de la classe Formation
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//use Symfony\Component\Form\Extension\Core\Type\EntityType;  // Utilisé pour la sélection d'entité


class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreEvent')
            ->add('dateEvent', null, [
                'widget' => 'single_text',
            ])
            ->add('lieuEvent')
            ->add('statutEvent')
            // Ajout du champ pour la formation
            ->add('formation', EntityType::class, [
                'class' => Formation::class,  // La classe de l'entité Formation
                'choice_label' => 'titreFor', // Affichage du titre de la formation dans la liste déroulante
                'placeholder' => 'Sélectionner une formation', // Texte à afficher par défaut
                'required' => false, // Rendre ce champ optionnel, tu peux le mettre à true si c'est obligatoire
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}

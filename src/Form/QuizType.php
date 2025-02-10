<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('questionQuiz', TextType::class, [
                'label' => 'Question',
                'required' => true
            ])
            ->add('categorieSant', ChoiceType::class, [
                'choices' => [
                    'Santé mentale' => 'Santé mentale',
                    'placeholder' => 'Choisissez une catégorie',
                ],
                'expanded' => false, // Affichage sous forme de dropdown
                'multiple' => false, // Pas de multiple choix
                'data' => 'Santé mentale', // Valeur par défaut
            ])

            // Ajouter les réponses possibles avec les points associés
            ->add('reponsesQuiz', CollectionType::class, [
                'entry_type' => ReponseQuizType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'label' => 'Réponses',
            ])

            // ->add('reponseQuiz', ChoiceType::class, [
            //     'choices' => [
            //         'Oui' => 5,
            //         'Parfois' => 3,
            //         'Non' => 0
            //     ],
            //     'expanded' => true, // Boutons radio
            //     'multiple' => false // Une seule réponse possible
            // ])
            // ->add('scoreQuiz', HiddenType::class, [
            //     'mapped' => false // Ne pas stocker directement dans l'entité
            // ])
            // ->add('consultationQ', EntityType::class, [
            //     'class' => Consultation::class,
            //     'choice_label' => 'id',
            //     'placeholder' => 'Associer à une consultation'
            // ]);
            // ->add('submit', SubmitType::class, ['label' => 'Valider']);
            // Soumettre le formulaire
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter la question'
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}

<?php
// src/Form/QuizUserResponseType.php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuizUserResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $questions = $options['questions'];

        foreach ($questions as $question) {
            $choices = [];
            // Remplir le tableau de choix avec les réponses de la question et leurs scores
            foreach ($question->getReponses() as $reponse) {
                $choices[$reponse->getAnswerText()] = $reponse->getScore(); // Associer le texte de la réponse avec le score
            }

            // Ajout de la question au formulaire avec les choix de réponses
            $builder->add('question_' . $question->getId(), ChoiceType::class, [
                'choices' => $choices,
                'expanded' => true,  // Boutons radio pour chaque réponse
                'multiple' => false, // Une seule réponse possible
                'label' => $question->getQuestionText(), // Question affichée
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'questions' => [],  // Liste des questions passées en paramètre
        ]);
    }
}

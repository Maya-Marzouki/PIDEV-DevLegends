<?php
namespace App\Form;

use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QuizUserResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupérer toutes les questions de la base
        $questions = $options['questions'];  // Les questions passées dans les options

        foreach ($questions as $question) {
            $choices = [];
            // Récupérer les réponses et points associés
            foreach ($question->getReponsesQuiz() as $reponse) {
                $choices[$reponse['reponseText']] = $reponse['points'];  // Associe chaque réponse avec les points
            }

            $builder->add('question_'.$question->getId(), ChoiceType::class, [
                'label' => $question->getQuestionQuiz(),
                'choices' => $choices,
                'expanded' => true,  // Boutons radio
                'multiple' => false, // Une seule réponse possible
            ]);
        }

        // Ajouter le bouton de soumission du quiz
        $builder->add('submit', SubmitType::class, [
            'label' => 'Valider'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
            'questions' => [],  // Passer les questions dans les options
        ]);
    }
}

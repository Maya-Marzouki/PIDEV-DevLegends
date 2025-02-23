<?php
namespace App\Form;

use App\Entity\Reponse;
use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReponseQuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $questions = $options['questions'];
        $builder
            ->add('answerText', ChoiceType::class, [
                'choices' => [
                    'Oui' => 'Oui',
                    'Non' => 'Non',
                    'Parfois' => 'Parfois',
                ],
                'expanded' => true, // Afficher les choix sous forme de boutons radio
                'multiple' => false, // Un seul choix possible
                'label' => 'Réponse',
                'attr' => ['class' => 'reponse-options'],
            ])
            ->add('score', IntegerType::class, [
                'label' => 'Points associés',
                'attr' => ['placeholder' => 'Entrez les points', 'class' => 'form-control'],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Le score est requis.']),
                //     new Assert\Type([
                //         'type' => 'integer',
                //         'message' => 'Le score doit être un nombre entier.'
                //     ]),
                //     new Assert\Range([
                //         'min' => 0,
                //         'max' => 10,
                //         'notInRangeMessage' => 'Le score doit être compris entre {{ min }} et {{ max }}.'
                //     ])
                // ]
            ])
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choices' => $questions,
                'choice_label' => 'questionText',  // Assurez-vous que la classe Question a un champ 'questionText'
                'label' => 'Question associée',
                'placeholder' => 'Sélectionner une question',
                'attr' => ['class' => 'form-control'],

            ]);
            // ->add('answerType', ChoiceType::class, [
            //     'choices' => [], // Dynamically filled based on the selected question
            //     'label' => 'Type de Réponse',
            //     'required' => true,
            //     'multiple' => false, // Only one answer can be selected
            //     'expanded' => true, // To render radio buttons instead of a select dropdown
            //     'mapped' => false, // This will not map to any field in the entity directly
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,  // Lier ce formulaire à l'entité Reponse
            'questions' => [], // Définir l'option "questions" avec une valeur par défaut (un tableau vide)
        ]);

        // Optionnel : définir le type de l'option "questions" pour une meilleure validation
        $resolver->setAllowedTypes('questions', 'array');
    }
}

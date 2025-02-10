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

class ReponseQuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('answerText', TextType::class, [
                'label' => 'Réponse',
                'attr' => ['placeholder' => 'Entrez le texte de la réponse'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le texte de la réponse est requis.']),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 255,
                        'minMessage' => 'La réponse doit contenir au moins {{ limit }} caractère.',
                        'maxMessage' => 'La réponse ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ]
            ])
            ->add('score', IntegerType::class, [
                'label' => 'Points associés',
                'attr' => ['placeholder' => 'Entrez les points', 'class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le score est requis.']),
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'Le score doit être un nombre entier.'
                    ]),
                    new Assert\Range([
                        'min' => 0,
                        'max' => 10,
                        'notInRangeMessage' => 'Le score doit être compris entre {{ min }} et {{ max }}.'
                    ])
                ]
            ])
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'questionText',  // Assurez-vous que la classe Question a un champ 'questionText'
                'label' => 'Question associée',
                'placeholder' => 'Sélectionner une question',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotNull(['message' => 'Une réponse doit être associée à une question.'])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,  // Lier ce formulaire à l'entité Reponse
            'validation_groups' => ['Default'], // Activation de la validation
        ]);
    }
}

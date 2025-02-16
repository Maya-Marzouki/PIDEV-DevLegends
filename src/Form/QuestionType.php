<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('questionText', TextType::class, [
                'label' => 'Question',
                'attr' => ['class' => 'form-control'],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'La question ne peut pas être vide.']),
                //     new Assert\Length([
                //         'max' => 255,
                //         'maxMessage' => 'La question ne peut pas dépasser {{ limit }} caractères.'
                //     ]),
                // ],
            ])
            ->add('answerType', ChoiceType::class, [
                'label' => 'Type de réponse',
                'choices' => [
                    'Oui' => 'Oui',
                    'Non' => 'Non',
                    'Parfois' => 'Parfois',
                    // 'Parfois / Rarement / Jamais' => 'sometimes_rarely_never',
                    // 'Échelle (1-5)' => 'scale_1_5',
                ],
                // 'attr' => ['class' => 'form-control'],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Le type de réponse est obligatoire.']),
                // ],
            ])
            ->add('points', IntegerType::class, [
                'label' => 'Points attribués',
                'attr' => ['class' => 'form-control'],
                // 'constraints' => [
                //     new Assert\Type(['type' => 'integer', 'message' => 'Les points doivent être un nombre entier.']),
                //     new Assert\GreaterThanOrEqual([
                //         'value' => 0,
                //         'message' => 'Les points doivent être supérieurs ou égaux à zéro.',
                //     ]),
                // ],
            ]);
            // ->add('save', SubmitType::class, [
            //     'label' => 'save',
            //     'attr' => ['class' => 'btn btn-primary mt-3'],
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}

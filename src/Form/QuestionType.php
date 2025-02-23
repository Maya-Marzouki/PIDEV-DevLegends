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

            ])
            ->add('answerType', ChoiceType::class, [
                'label' => 'Type de réponse',
                'choices' => [
                    'Oui, Non, Parfois' => 'group1',
                    // 'Fréquemment, Parfois, Rarement' => 'group2',
                    // 'Jamais, Parfois, Rarement' => 'group3'
                ],
            ])
            ->add('points', IntegerType::class, [
                'label' => 'Points attribués',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Consultation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('dateCons', DateType::class, [
            'widget' => 'single_text',
            'attr' => [
                'required' => true,
                'min' => (new \DateTime('today'))->format('Y-m-d'), // Date minimale = aujourd'hui
            ],
            // 'constraints' => [
            //     new Assert\NotBlank(['message' => 'La date est requise.']),
            //     new Assert\GreaterThanOrEqual([
            //         'value' => 'today',
            //         'message' => 'La date ne peut pas être antérieure à aujourd’hui.',
            //     ])
            // ],
        ])
            ->add('lienVisioCons', TextType::class, [
                'label' => 'Lien de la Consultation',
                'attr' => ['class' => 'form-control',
                'required' => true,
                'placeholder' => 'https://example.com',
            ],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Le lien est obligatoire.']),
                //     new Assert\Url(['message' => 'Veuillez entrer une URL valide.']),
                // ],
            ])
            // ->add('scoreMental', IntegerType::class, [
            //     'label' => 'Score Mental'
            // ])
            // ->add('etatMental', TextType::class, [
            //     'label' => 'État Mental'
            // ])
            ->add('notesCons', TextareaType::class, [
                'label' => 'Remarques',
                'attr' => ['class' => 'form-control',
                'required' => true,
                'minlength' => 10,
                'maxlength' => 1000,
                'placeholder' => 'Remarques',
            ],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Les notes sont obligatoires.']),
                //     new Assert\Length([
                //         'min' => 10,
                //         'max' => 1000,
                //         'minMessage' => 'Les notes doivent contenir au moins {{ limit }} caractères.',
                //         'maxMessage' => 'Les notes ne doivent pas dépasser {{ limit }} caractères.',
                // //     ]),
                // ],
            ])
            ->add('nom', TextareaType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control',
                'required' => true,
                'minlength' => 2,
                'maxlength' => 255,
                'placeholder' => 'Nom',
                ],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Le nom obligatoires.']),
                //     new Assert\Length([
                //         'min' => 3,
                //         'max' => 100,
                //         'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                //         'maxMessage' => 'Le nom ne doit pas dépasser {{ limit }} caractères.',
                //     ]),
                // ],
            ])
            ->add('prenom', TextareaType::class, [
                'label' => 'Prenom',
                'attr' => ['class' => 'form-control',
                'required' => true,
                'minlength' => 2,
                'maxlength' => 255,
                'placeholder' => 'Prenom',
                ],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Le prénom obligatoires.']),
                //     new Assert\Length([
                //         'min' => 3,
                //         'max' => 100,
                //         'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                //         'maxMessage' => 'Le prénom ne doit pas dépasser {{ limit }} caractères.',
                //     ]),
                // ],
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'attr' => ['class' => 'form-control',
                'required' => true,
                'min' => 0,
                'max' => 120,
                'placeholder' => 'Age',
            ],
            // 'constraints' => [
            //     new Assert\NotBlank(['message' => 'Le champs age est obligatoire.']),
            //     new Assert\Range([
            //         'min' => 0,
            //         'max' => 120,
            //         'notInRangeMessage' => 'Le champs age doit être compris entre {{ min }} et {{ max }}.',
            //     ]),
            // ],
            ]);
            // ->add('save', SubmitType::class, [
            //     'label' => 'Enregistrer'
            // ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}

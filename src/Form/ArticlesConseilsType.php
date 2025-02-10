<?php

namespace App\Form;

use App\Entity\ArticlesConseils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ArticlesConseilsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreArticle', TextType::class, [
                'label' => 'Titre de l\'article',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le titre de l\'article ne peut pas être vide.']),
                    new Assert\Length([
                        'min' => 5,
                        'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères.',
                        'max' => 255
                    ])
                ]
            ])
            ->add('contenuArticle', TextareaType::class, [
                'label' => 'Contenu de l\'article',
                'attr' => ['class' => 'form-control', 'rows' => 5],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le contenu de l\'article ne peut pas être vide.']),
                    new Assert\Length([
                        'min' => 20,
                        'minMessage' => 'Le contenu doit contenir au moins {{ limit }} caractères.'
                    ])
                ]
            ])
            ->add('categorieMentalArticle', TextType::class, [
                'label' => 'Catégorie mentale',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La catégorie mentale est obligatoire.'])
                ]
            ])
            ->add('image', TextType::class, [
                'label' => 'URL de l\'image',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez l\'URL de l\'image'],
                'constraints' => [
                    new Assert\Url(['message' => 'L\'URL de l\'image n\'est pas valide.'])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticlesConseils::class,
            'validation_groups' => ['Default'], // Activation de la validation
        ]);
    }
}

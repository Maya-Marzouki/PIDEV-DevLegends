<?php

namespace App\Form;

use App\Entity\ArticlesConseils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;
class ArticlesConseilsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreArticle', TextType::class, [
                'label' => 'Titre de l\'article',
                'attr' => ['class' => 'form-control'],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Le titre de l\'article ne peut pas être vide.']),
                //     new Assert\Length([
                //         'min' => 5,
                //         'minMessage' => 'Le titre doit contenir au moins {{ limit }} caractères.',
                //         'max' => 255
                //     ])
                // ]
            ])
            ->add('contenuArticle', TextareaType::class, [
                'label' => 'Contenu de l\'article',
                'attr' => ['class' => 'form-control', 'rows' => 5],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'Le contenu de l\'article ne peut pas être vide.']),
                //     new Assert\Length([
                //         'min' => 20,
                //         'minMessage' => 'Le contenu doit contenir au moins {{ limit }} caractères.'
                //     ])
                // ]
            ])
            ->add('categorieMentalArticle', TextType::class, [
                'label' => 'Catégorie mentale',
                'attr' => ['class' => 'form-control'],
                // 'constraints' => [
                //     new Assert\NotBlank(['message' => 'La catégorie mentale est obligatoire.'])
                // ]
            ])
            ->add('image', FileType::class, [
                'mapped' => false, // Ne pas lier directement à l’entité
                'required' => false, // L’upload n’est pas obligatoire
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG ou PNG).',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticlesConseils::class,
            // 'validation_groups' => ['Default'], // Activation de la validation
        ]);
    }
}

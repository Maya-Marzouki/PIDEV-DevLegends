<?php

namespace App\Form;

use App\Entity\Profil;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('userAge')
            ->add('userRole', ChoiceType::class, [
                'choices' => [
                    'Patient' => 'Patient',
                    'Medecin' => 'Medecin',
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Rôle',
            ])
            ->add('docSpecialty', ChoiceType::class, [
                'choices' => [
                    'Psychiatre' => 'Psychiatre',
                    'Psychologue' => 'Psychologue',
                    'Psychotherapeute' => 'Psychotherapeute',
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Spécialité',
            ])
            ->add('userAge', TextType::class)
            ->add('userPicture', FileType::class, [
                'label' => 'Photo de profile',
                'attr' => ['class' => 'form-control'],
                'mapped' => false, 
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger un type d\'image valide(JPG, JPEG or PNG).',
                    ])
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profil::class,
        ]);
    }
}

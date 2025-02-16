<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('userEmail')
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('userRole', ChoiceType::class, [
                'choices' => [
                    'Patient' => 'Patient',
                    'Medecin' => 'Medecin',
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Role',
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
            ->add('userAge')
            ->add('userPicture', FileType::class, [
                'label' => 'Photo de profile',
                'attr' => ['class' => 'form-control'],
                'mapped' => false, 
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger un type d image valide(JPG, JPEG or PNG).',
                    ])
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

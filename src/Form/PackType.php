<?php

namespace App\Form;

use App\Entity\Pack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPack')
            ->add('descriptPack')
            ->add('prixPack')
            ->add('dureePack')
            ->add('photoPack', FileType::class, [
                'label' => 'Photo du pack (JPG, PNG)',
                'mapped' => false, // Ne pas lier directement à l’entité
                'required' => false, // L’upload n’est pas obligatoire
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG ou PNG).',
                    ])
                ],
            ])
            ->add('discountCode', TextType::class, [
                'label' => 'Code de réduction',
                'required' => false, // Le champ n'est pas obligatoire
            ]);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pack::class,
        ]);
    }
}
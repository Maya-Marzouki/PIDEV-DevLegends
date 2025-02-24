<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProduit')
            ->add('prixProduit')
            ->add('qteProduit')
             // Statut, avec "Indisponible" par défaut
             ->add('statutProduit', ChoiceType::class, [
                'choices' => [
                    'Disponible' => true,    
                    'Indisponible' => false  
                ],
                'label' => 'Statut',
                'data' => false,  // Par défaut, l'état du statut est "Indisponible"
                'attr' => ['class' => 'form-control',
            

        ],
                
            ])
            ->add('categorieProduit', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nomCategorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

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
                    'Disponible' => Produit::STATUT_DISPONIBLE,
                    'Indisponible' => Produit::STATUT_INDISPONIBLE
                ],
                'label' => 'Statut',
                'disabled' => true,  // Désactiver pour éviter une modification manuelle
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

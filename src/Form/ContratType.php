<?php

namespace App\Form;

use App\Entity\Centre;
use App\Entity\Contrat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datdebCont', null, [
                'widget' => 'single_text',
            ])
            ->add('datfinCont', null, [
                'widget' => 'single_text',
            ])
            ->add('modpaimentCont', ChoiceType::class, [
                'choices' => [
                    'Chèque' => 'cheque',
                    'Carte Bancaire' => 'carte',
                ],
                'attr' => ['class' => 'form-control'],
                'label' => 'Mode de paiement',
            ])
            ->add('renouvAutoCont')
            ->add('centre', EntityType::class, [
                'class' => Centre::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
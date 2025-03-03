<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomClient')
            ->add('adresseEmail')
            ->add('dateCommande', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'data' => $options['data']->getDateCommande() ?? new \DateTime(),
            ])
            ->add('adresse')
            // ->add('modePaiement', ChoiceType::class, [
            //     'choices' => [
            //         'Carte Bancaire' => 'carte',
            //         'Virement Bancaire' => 'virement',
            //         'PayPal' => 'paypal',
            //     ],
            //     'mapped' => false,
            //     'expanded' => true,
            //     'multiple' => false,
            // ])
            // ->add('numeroCarte', TextType::class, ['mapped' => false, 'required' => false])
            // ->add('numeroVirement', TextType::class, ['mapped' => false, 'required' => false])
            // ->add('paypalEmail', TextType::class, ['mapped' => false, 'required' => false])
            ->add('pays', TextType::class, [
                'data' => 'Tunisie',
                'disabled' => true,
                'required' => true,
            ])
            ->add('NumTelephone', TextType::class, ['required' => true])
            ->add('totalCom', HiddenType::class, [
                'data' => $options['totalCom'],
                'mapped' => false,
            ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'totalCom' => null,  // Option pour passer le total
        ]);
    }
}

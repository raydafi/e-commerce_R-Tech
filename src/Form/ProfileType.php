<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // CORRECTION ICI : first_name
            ->add('first_name', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => ['placeholder' => 'Jean', 'class' => 'form-control']
            ])
            // CORRECTION ICI : last_name
            ->add('last_name', TextType::class, [
                'label' => 'Votre nom',
                'attr' => ['placeholder' => 'Dupont', 'class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour',
                'attr' => ['class' => 'btn-submit', 'style' => 'margin-top: 15px; background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
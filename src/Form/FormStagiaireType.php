<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormStagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, ['attr'=> ['class' => 'form-control']])
            ->add('lastname', TextType::class, ['attr'=> ['class' => 'form-control']])
            ->add('birth_date', DateType::class, ['widget' => 'single_text', 'attr' => ['class' => 'form-control datepicker']])
            ->add('gender', TextType::class, ['attr'=> ['class' => 'form-control']])
            ->add('city', TextType::class, ['attr'=> ['class' => 'form-control']])
            ->add('cp', TextType::class, ['attr'=> ['class' => 'form-control']])
            ->add('mail', TextType::class, ['attr'=> ['class' => 'form-control']])
            ->add('phone_number', TextType::class, ['attr'=> ['class' => 'form-control']])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-secondary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}

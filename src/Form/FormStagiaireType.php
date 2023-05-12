<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormStagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('birth_date', DateType::class)
            ->add('gender', TextType::class)
            ->add('city', TextType::class)
            ->add('cp', TextType::class)
            ->add('mail', TextType::class)
            ->add('phone_number', TextType::class)
            ->add('sessions', EntityClass::class, [
                'class' => Session::class,
                'choice_label' => 'name',])
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

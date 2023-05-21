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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['attr'=> ['class' => 'form-control']])
            ->add('date_begin', DateType::class, [
                'widget' => 'single_text',
                'attr'=> ['class' => 'form-control']])
            ->add('date_end', DateType::class, [
                'widget' => 'single_text',
                'attr'=> ['class' => 'form-control']])
            ->add('Nb_place', IntegerType::class, ['attr'=> ['class' => 'form-control']])
            ->add('stagiaires', EntityType::class, [
                'class' => Stagiaire::class,
                'choice_label' => 'firstname',
                'multiple' => true,
                'expanded' => true,
                'attr'=> ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-secondary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use App\Form\ProgrammeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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

            // ->add('stagiaires', EntityType::class, [
            //     'class' => Stagiaire::class,
            //     'choice_label' => 'firstname',
            //     'multiple' => true,
            //     'expanded' => true,
            //     'attr'=> ['class' => 'form-control']
            // ])

            // la collection attends l'élément qu'elle entrera dans le form
            // ce n'est pas obligatoire que ce soit un autre form
            ->add('programmes', CollectionType::class, [
                'entry_type' => ProgrammeType::class,
                'prototype' => true,
                //on va autorisé l'ajout de nouveau élément dans Session qui seront persister grace au cascade_persist sur l'élement ProgrammeType
                //cela va activé le data prototype qui sera un attribut html qu'on pourra manipuler en JS
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,// obligatoire car Session n'a pas de SetProgramme mais c'est programme qui contient cette Session
                //Programm est propriétaire de la relation
                // pour évité un mapping false on est obligé de rajouté un by_reference false
            ])

            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}

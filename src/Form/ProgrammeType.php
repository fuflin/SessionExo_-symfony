<?php

namespace App\Form;

use App\Entity\Programme;
use App\Entity\ModuleSession;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('session', HiddenType::class)
            ->add('module', EntityType::class, [
                'class' => ModuleSession::class,
                'choice_label' => 'name',
            ])
            ->add('nb_jours', IntegerType::class, [
                'label' => 'Durée en jours',
                'attr' => ['min' => 1, 'max' =>100],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}

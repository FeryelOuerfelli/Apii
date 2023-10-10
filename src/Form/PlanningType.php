<?php

namespace App\Form;

use App\Entity\Planning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_debut', DateType::class, [
               
                'label' => 'Date Debut',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-control form-group'],
                'required' => true
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'Date Fin',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-control form-group'],
                'required' => true
            ])
            ->add('heure_debut', TimeType::class, [
                'label' => 'Heure Debut',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control form-group'],
                'required' => true
            ])
            ->add('heure_fin', TimeType::class, [
                'label' => 'Heure Fin',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control form-group'],
                'required' => true
            ])
            ->add('description',  TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => true
         

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,

        ]);
    }
}

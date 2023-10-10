<?php

namespace App\Form;

use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('imagem', FileType::class, [
            'label' => 'Votre image de profil (Des fichiers images uniquement)',
            // unmapped means that this field is not associated to any entity property
            'mapped' => false,
            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,
            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/gif',
                        'image/jpeg',
                        'image/png',
                        'image/jpg',
                    ],
                    'mimeTypesMessage' => 'Veuillez renseigner votre Image',

                ]),
                new NotBlank(['message'=>('Veuillez renseigner votre image')])
            ],
        ])
            ->add('num_serie')
            ->add('nom')
            ->add('marque')
            ->add('date_achat',DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [new NotBlank(['message'=>('Veuillez renseigner votre date de naissance')])]
            ])

            ->add('etat',ChoiceType::class, [
                'choices'  => [
                    'En Panne' => "En Panne",
                    'En Stock' =>"En Stock" ,
                    'En Utilisation' =>"En Utilisation" 
                ]],
            )            
            ->add('affectation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}

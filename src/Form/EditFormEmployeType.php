<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EditFormEmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[ 'disabled' => true])

            ->add('prenom',TextType::class,[ 'disabled' => true])

            ->add('date_de_naissance',DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'constraints' => [new NotBlank(['message'=>('Veuillez renseigner votre date de naissance')])]
            ])
            ->add('email',EmailType::class)
            ->add('num_tel',TextType::class,[
                'constraints' => [new NotBlank(['message'=>('Veuillez renseigner votre numéro de téléphone')]),
                    new Regex(pattern:"/^[0-9]*$/", message:"Votre numéro de téléphone n'est pas valide")
                    ]
            ])
            ->add('sexe',ChoiceType::class, [
                'choices'  => [
                    'Femme' => "femme",
                    'Homme' =>"Homme"
                ]],
            )
            ->add('description',TextareaType::class,[
                'constraints' => [new NotBlank(['message'=>('Veuillez renseigner votre description')]),
                    new Length(
                        min:8,
                        max: 1000,
                        minMessage: 'Votre description doit comporter au moins {{ limit }} caractères',
                        maxMessage: 'Votre description ne peut pas dépasser {{ limit }} caractères',
                    )
                    ]
            ])
            ->add('region',ChoiceType::class, [
                'choices'  => [
             'Ariana' =>"Ariana",
           'Béja'=>"Béja",
             'Ben Arous'=>"Ben Arous",
              'Bizerte'=>"Bizerte",
             'Gabès'=>"Gabès",
              'Gafsa'=>"Gafsa",
              'Jendouba'=>"Jendouba",
             'Kairouan'=>"Kairouan",
             'Kasserine'=>"Kasserine",
             'Kébili'=>"Kébili",
             'Kef'=>"Kef",
             'Mahdia'=>"Mahdia",
             'Manouba'=>"Manouba",
            'Médenine'=>"Médenine",
             'Monastir'=>"Monastir",
           'Nabeul'=>"Nabeul",
             'Sfax'=>"Sfax",
            'Sidi Bouzid'=>"Sidi Bouzid",
           'Siliana'=>"Siliana",
           'Sousse'=>"Sousse",
            'Tataouine'=>"Tataouine",
             'Tozeur'=>"Tozeur",
           'Tunis'=>"Tunis",
           'Zaghouan'=>"Zaghouan"   ]]) 
           ->add('structure',ChoiceType::class, [
            'choices'  => [
         'Pépinière' =>"Pépinière",
       'Direction'=>"Direction",
         'Siège'=>"Siège"
           ]]) 
            ->add('adresse',TextType::class,
                ['constraints' => [new NotBlank(['message'=>('Veuillez renseigner votre adresse')]),
                    new Length(
                        min:3,
                        max: 30,
                        minMessage: 'Votre adresse doit comporter au moins {{ limit }} caractères',
                        maxMessage: 'Votre adresse ne peut pas dépasser {{ limit }} caractères',
                    )
                    ]])

            ->add('specialite',TextType::class,[
                'constraints' => [new NotBlank(['message'=>('Veuillez renseigner votre spécialité')]),
                    new Length(
                        min:3,
                        max: 30,
                        minMessage: 'Votre spécialité doit comporter au moins {{ limit }} caractères',
                        maxMessage: 'Votre spécialité ne peut pas dépasser {{ limit }} caractères',
                    )
                    ]
            ])
            ->add('photo', FileType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

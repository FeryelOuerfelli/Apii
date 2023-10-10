<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password',RepeatedType::class,array(
                'type'=>PasswordType::class,
                'first_options'=> array('label'=>'password',
                    'constraints' => [new notBlank(['message'=>('Veuillez renseigner votre mot de passe')]),
                        new Regex(pattern:"/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", message:"Votre mot de passe doit comporter au moins huit caractères, dont des lettres majuscules et minuscules, un chiffre et un symbole")]
                    ),
                'second_options'=> array('label'=> 'Confirm password',
                    'constraints' => [new notBlank(['message'=>('Veuillez renseigner votre confirmation de mot de passe')])],
                ),
                'invalid_message' => 'Les mots de passe que vous avez entrés ne sont pas identiques.'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

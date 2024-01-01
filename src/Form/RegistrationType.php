<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength'=> '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom / Prénom',
                'label_attr' => [
                    'class' => 'form_label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>' 2','max'=> '50']),
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength'=> '2',
                    'maxlength' => '50'
                ],
                'label' => 'Pseudo (Facultatif)',
                'label_attr' => [
                    'class' => 'form_label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>' 2','max'=> '50']),
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength'=> '2',
                    'maxlength' => '180'
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form_label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min'=>' 2','max'=> '180']),
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' =>[
                    'attr' => [
                        'class' =>'form-control',
                    ],
                    'label' => 'Mot de passe',
                    'label_attr'=> [
                        'class' =>  'form_label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min'=>' 2','max'=> '180']),
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'class' =>'form-control',
                    ],
                    'label_attr'=> [
                        'class' =>  'form_label mt-4'
                    ],
                    'label' =>'Confirmation du mot de passe',
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['min'=>' 2','max'=> '180']),
                    ]
                ],
                'invalid_message'=> 'Les mots de passe ne corrspondent pas.'
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class'=> 'btn btn-primary mt-4'
                ],
                'label' => 'Enregistrer',
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

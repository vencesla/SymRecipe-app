<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class UserPasswordType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
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
                ]
            ],
            'invalid_message'=> 'Les mots de passe ne corrspondent pas.'
        ])
        ->add('newPassword', PasswordType::class, [
            'attr' => [
                'class' => 'form-control',
            ],
            'label' =>'Nouveau mot de passe',
            'label_attr'=> [
                'class' => 'form_label'
            ],
            'constraints' => [
                new Assert\NotBlank(),
            ]
        ])
        ->add("submit", SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4'
            ],
            'label'=> 'Changer mon mot de passe'
        ]);
    }
}
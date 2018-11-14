<?php

namespace Lle\OAuthClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lle\OAuthClientBundle\Service\OAuthApi;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class EditUserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, ['label'=> 'Nom', 'attr' => ['class'=> 'form-control']])
            ->add('firstname', TextType::class, ['label'=> 'Prenom', 'attr' => ['class'=> 'form-control']])
            ->add('email', TextType::class, ['attr' => ['class'=> 'form-control']])
            ->add('mobile', TextType::class, ['required'=>false,'attr' => ['class'=> 'form-control']])
            ->add('password', RepeatedType::class, [
                'required'=>false,
                'type'=> PasswordType::class,
                'first_options' => ['required'=>false,'label' => 'Mot de passe', 'attr' => ['class'=> 'form-control']],
                'second_options' => ['required'=>false,'label' => 'Répéter mot de passe', 'attr' => ['class'=> 'form-control']]
            ])
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => ['class'=> 'btn btn-primary']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

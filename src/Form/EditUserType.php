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
            ->add('lastname', TextType::class, ['label'=> 'label.name', 'attr' => ['class'=> 'form-control']])
            ->add('firstname', TextType::class, ['label'=> 'label.lastname', 'attr' => ['class'=> 'form-control']])
            ->add('email', TextType::class, ['label'=>'label.email','attr' => ['class'=> 'form-control']])
            ->add('mobile', TextType::class, ['label'=>'label.mobile','required'=>false,'attr' => ['class'=> 'form-control']])
            ->add('password', RepeatedType::class, [
                'required'=>false,
                'type'=> PasswordType::class,
                'first_options' => ['required'=>false,'label' => 'label.password', 'attr' => ['class'=> 'form-control']],
                'second_options' => ['required'=>false,'label' => 'label.repeat_password', 'attr' => ['class'=> 'form-control']]
            ])
            ->add('save', SubmitType::class, array('label' => 'label.save', 'attr' => ['class'=> 'btn btn-primary']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'LleOAuth'
        ]);
    }
}

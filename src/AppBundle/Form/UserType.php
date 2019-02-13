<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        setlocale(LC_TIME, 'fr_FR');
        $builder
            ->add('prenom', TextType::class, ['label'=>'Prénom', 'attr'=>['placeholder' => 'Prénom']])
            ->add('nom', TextType::class, ['label'=>'Nom', 'attr'=>['placeholder' => 'Nom']])
            ->add('birthdate', BirthdayType::class, ['label'=>'Date de naissance', 'format'=>'ddMMyyyy'])
            ->add('email', EmailType::class, ['label'=>'Adresse e-mail', 'attr'=>['placeholder' => 'Adresse e-mail']])
//            ->add('registrationDate')
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'invalid_message' => 'Les mots de passe ne sont pas identiques',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ])
            ->add('groups', EntityType::class, array(
                'label'=>'Groupes',
                'class'=>'AppBundle:Groupe',
                'choice_label'=>'name',
                'multiple'=>true,
                'expanded'=>true,
                'required'=>false,
                'by_reference'=>false,
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }

}

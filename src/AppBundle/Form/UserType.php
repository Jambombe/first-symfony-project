<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
            ->add('groups', EntityType::class, array(
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

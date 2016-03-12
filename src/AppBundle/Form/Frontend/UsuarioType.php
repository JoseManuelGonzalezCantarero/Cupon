<?php

namespace AppBundle\Form\Frontend;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('apellidos', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Las dos contraseñas deben coincidir',
                'first_options' => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Repite Contraseña'),
            ))
            ->add('direccion', TextType::class)
            ->add('permite_email', CheckboxType::class, array('required'=>false))
            ->add('fecha_nacimiento', BirthdayType::class)
            ->add('dni', TextType::class)
            ->add('numero_tarjeta', TextType::class)
            ->add('ciudad', EntityType::class, array('class' => 'AppBundle\Entity\Ciudad'))
            ->add('registrarme', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Usuario'
        ));
    }
}

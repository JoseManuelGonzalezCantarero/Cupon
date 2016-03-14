<?php

namespace AppBundle\Form\Extranet;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TiendaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('login', TextType::class, array('attr' => array(
                'readonly' => true
            )))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Las dos contraseñas deben coincidir',
                'first_options'   => array('label' => 'Contraseña'),
                'second_options'  => array('label' => 'Repite Contraseña'),
                'required'        => false
            ))
            ->add('descripcion')
            ->add('direccion')
            ->add('ciudad', EntityType::class, array('class' => 'AppBundle\Entity\Ciudad'))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar cambios'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Tienda'
        ));
    }
}

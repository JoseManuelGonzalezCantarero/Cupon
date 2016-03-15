<?php

namespace AppBundle\Form\Extranet;

use AppBundle\EventListener\OfertaTypeListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfertaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('condiciones')
            ->add('foto', FileType::class, array('required' => false))
            ->add('precio', MoneyType::class)
            ->add('descuento', MoneyType::class)
            ->add('umbral')
            ->add('guardar', SubmitType::class, array('label' => 'Guardar Cambios'));
        if($options['data']->getId() == null)
        {
            $builder->add('acepto', CheckboxType::class, array(
                'mapped' => false
            ));

            $listener = new OfertaTypeListener();
            $builder->addEventListener(FormEvents::PRE_SUBMIT, array($listener, 'preSubmit'));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' => 'AppBundle\Entity\Oferta'
        ));
    }
}
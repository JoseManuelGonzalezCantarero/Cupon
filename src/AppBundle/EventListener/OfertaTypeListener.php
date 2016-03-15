<?php

namespace AppBundle\EventListener;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;

class OfertaTypeListener
{
    public function preSubmit(FormEvent $event)
    {
        $formulario = $event->getForm();

        if($formulario->get('acepto')->getData() == false)
        {
            $formulario->get('acepto')->addError(new FormError('Debes aceptar las condiciones legales.'));
        }
    }
}
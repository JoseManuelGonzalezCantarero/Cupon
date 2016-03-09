<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CiudadController extends Controller
{
    /**
     * @Route("/ciudad/cambiar-a-{ciudad}/", name = "ciudadCambiar")
     */
    public function cambiarAction($ciudad)
    {
        return new RedirectResponse($this->generateUrl('portada', array(
                'ciudad' => $ciudad
            )
        ));
    }
}

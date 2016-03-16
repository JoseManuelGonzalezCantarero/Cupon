<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BackendController extends Controller
{
    /**
     * @param $ciudad
     * @return RedirectResponse
     * @Route("/cambiar-a-{ciudad}/", name="backendCiudadCambiar")
     */
    public function ciudadCambiarAction($ciudad)
    {
        $this->get('request')->getSession()->set('ciudad', $ciudad);

        $dondeEstaba = $this->get('request')->server->get('HTTP_REFERER');
        return new RedirectResponse($dondeEstaba, 302);
    }
}

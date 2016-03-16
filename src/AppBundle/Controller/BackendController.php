<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BackendController extends Controller
{
    public function ciudadCambiarAction($ciudad)
    {
        $this->get('request')->getSession()->set('ciudad', $ciudad);

        $dondeEstaba = $this->get('request')->server->get('HTTP_REFERER');
        return new RedirectResponse($dondeEstaba, 302);
    }
}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SitioController extends Controller
{
    /**
     * @param $pagina
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/sitio/{pagina}/")
     */
    public function estaticaAction($pagina)
    {
        return $this->render('estatico/'.$pagina.'.html.twig');
    }
}

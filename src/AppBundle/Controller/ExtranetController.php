<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExtranetController extends Controller
{
    /**
     * @Route("/extranet/", name="extranetPortada")
     */
    public function portadaAction()
    {

    }

    /**
     * @Route("/extranet/oferta/nueva/", name="extranetOfertaNueva")
     */
    public function ofertaNuevaAction()
    {

    }

    /**
     * @param $id
     * @Route("/extranet/oferta/editar/{id}/", name="extranetOfertaEditar")
     */
    public function ofertaEditarAction($id)
    {

    }

    /**
     * @param $id
     * @Route("/extranet/ventas/{id}/", name="extranetOfertaVentas")
     */
    public function ofertaVentasAction($id)
    {

    }

    /**
     * @Route("/extranet/perfil/", name="extranetPerfil")
     */
    public function perfilAction()
    {

    }

}

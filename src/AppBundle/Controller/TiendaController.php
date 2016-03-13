<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TiendaController extends Controller
{
    /**
     * @param $ciudad
     * @param $tienda
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{_locale}/{ciudad}/tiendas/{tienda}/", name="tiendaPortada", defaults={"_locale": "es"}, requirements={"_locale": "es|en"})
     */
    public function portadaAction($ciudad, $tienda, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $ciudad = $em->getRepository('AppBundle:Ciudad')->findOneBySlug($ciudad);

        $tienda = $em->getRepository('AppBundle:Tienda')->findOneBy(array(
            'slug' => $tienda,
            'ciudad' => $ciudad->getId()
        ));

        if(!$tienda)
        {
            throw $this->createNotFoundException('No existe esta tienda');
        }

        $ofertas = $em->getRepository('AppBundle:Tienda')->findUltimasOfertasPublicadas($tienda->getId());

        $cercanas = $em->getRepository('AppBundle:Tienda')
            ->findCercanas($tienda->getSlug(), $tienda->getCiudad()->getSlug());

        $formato = $request->getRequestFormat();

        return $this->render('tienda/portada.'.$formato.'.twig', array(
           'tienda' => $tienda,
            'ofertas' => $ofertas,
            'cercanas' => $cercanas
        ));
    }
}

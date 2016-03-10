<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TiendaController extends Controller
{
    /**
     * @param $ciudad
     * @param $tienda
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{ciudad}/tiendas/{tienda}/", name="tiendaPortada")
     */
    public function portadaAction($ciudad, $tienda)
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

        return $this->render('tienda/portada.html.twig', array(
           'tienda' => $tienda,
            'ofertas' => $ofertas,
            'cercanas' => $cercanas
        ));
    }
}

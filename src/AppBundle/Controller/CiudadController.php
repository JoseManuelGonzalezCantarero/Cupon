<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CiudadController extends Controller
{
    /**
     * @Route("/{_locale}/ciudad/cambiar-a-{ciudad}/", name = "ciudadCambiar", defaults={"_locale": "es"}, requirements={"_locale": "es|en"})
     * @param $ciudad
     * @return RedirectResponse
     */
    public function cambiarAction($ciudad)
    {
        return new RedirectResponse($this->generateUrl('portada', array(
                'ciudad' => $ciudad
            )
        ));
    }

    public function listaCiudadesAction($ciudad)
    {
        $em = $this->getDoctrine()->getManager();
        $ciudades = $em->getRepository('AppBundle:Ciudad')->findAll();

        return $this->render('ciudad/listaCiudades.html.twig', array(
            'ciudades' => $ciudades,
            'ciudadActual' => $ciudad
        ));
    }

    /**
     * @param $ciudad
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{_locale}/{ciudad}/recientes.{_format}/", name="ciudadRecientes", defaults={"_format": "html", "_locale": "es"},
     *          requirements={"_format": "html|rss", "_locale": "es|en"})
     */
    public function recientesAction($ciudad, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $ciudad = $em->getRepository('AppBundle:Ciudad')->findOneBySlug($ciudad);

        if (!$ciudad) {
            throw $this->createNotFoundException('No existe la ciudad');
        }

        $cercanas = $em->getRepository('AppBundle:Ciudad')->findCercanas($ciudad->getId());

        $ofertas = $em->getRepository('AppBundle:Oferta')->findRecientes($ciudad->getId());

        $formato = $request->getRequestFormat();

        return $this->render('ciudad/recientes.'.$formato.'.twig', array(
            'ciudad' => $ciudad,
            'cercanas' => $cercanas,
            'ofertas' => $ofertas
        ));
    }
}

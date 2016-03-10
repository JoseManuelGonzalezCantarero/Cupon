<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CiudadController extends Controller
{
    /**
     * @Route("/ciudad/cambiar-a-{ciudad}/", name = "ciudadCambiar")
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
     * @Route("/{ciudad}/recientes/", name="ciudadRecientes")
     */
    public function recientesAction($ciudad)
    {
        $em = $this->getDoctrine()->getManager();

        $ciudad = $em->getRepository('AppBundle:Ciudad')->findOneBySlug($ciudad);

        if (!$ciudad) {
            throw $this->createNotFoundException('No existe la ciudad');
        }

        $cercanas = $em->getRepository('AppBundle:Ciudad')->findCercanas($ciudad->getId());

        $ofertas = $em->getRepository('AppBundle:Oferta')->findRecientes($ciudad->getId());

        return $this->render('ciudad/recientes.html.twig', array(
            'ciudad' => $ciudad,
            'cercanas' => $cercanas,
            'ofertas' => $ofertas
        ));
    }
}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OfertaController extends Controller
{
    /**
     * @param $ciudad
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{_locale}/{ciudad}/ofertas/{slug}/", name="oferta", defaults={"_locale": "es"}, requirements={"_locale": "es|en"})
     */
    public function ofertaAction($ciudad, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('AppBundle:Oferta')
            ->findOferta($ciudad, $slug);

        if (!$oferta) {
            throw $this->createNotFoundException('No existe la oferta');
        }

        $relacionadas = $em->getRepository('AppBundle:Oferta')
            ->findRelacionadas($ciudad);

        return $this->render('oferta/detalle.html.twig', array(
            'oferta' => $oferta,
            'relacionadas' => $relacionadas
        ));
    }
}

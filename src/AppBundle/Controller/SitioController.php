<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SitioController extends Controller
{
    /**
     * @param $ciudad
     * @return RedirectResponse
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{_locale}/{ciudad}", name="portada", defaults={"_locale": "es"}, requirements={"_locale": "es|en"})
     */
    public function portadaAction($ciudad = null)
    {
        if($ciudad == null)
        {
            $ciudad = $this->getParameter('cupon.ciudad_por_defecto');
            return new RedirectResponse($this->generateUrl('portada', array('ciudad' => $ciudad)));
        }
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('AppBundle:Oferta')->findOfertaDelDia($ciudad);
        if(!$oferta)
        {
            throw $this->createNotFoundException(
                'No se ha encontrado la oferta del día en la ciudad seleccionada'
            );
        }
        return $this->render('portada.html.twig', array(
            'oferta' => $oferta
        ));
    }

    /**
     * @param $pagina
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{_locale}/sitio/{pagina}/", name="paginaEstatica", defaults={"_locale": "es"}, requirements={"_locale": "es|en"})
     */
    public function estaticaAction($pagina)
    {
        return $this->render('estatico/'.$pagina.'.html.twig');
    }
}

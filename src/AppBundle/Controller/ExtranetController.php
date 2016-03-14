<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('AppBundle:Oferta')->find($id);

        if(false === $this->get('security.authorization_checker')->isGranted('ROLE_EDITAR_OFERTA', $oferta))
        {
            throw new AccessDeniedException();
        }

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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/extranet/login/")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');
        return $this->render('extranet/login.html.twig', array(
            'error' => $helper->getLastAuthenticationError()
        ));
    }

    /**
     * @Route("/extranet/login_check/", name="extranetLoginCheck")
     */
    public function loginCheckAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/extranet/logout/", name="extranetLogout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }

}

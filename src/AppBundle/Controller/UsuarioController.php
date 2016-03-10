<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/usuario/compras/", name="usuarioCompras")
     */
    public function comprasAction()
    {
        $usuario_id = 1;

        $em = $this->getDoctrine()->getManager();
        $compras = $em->getRepository('AppBundle:Usuario')->findTodasLasCompras($usuario_id);

        return $this->render('usuario/compras.html.twig', array(
            'compras' => $compras
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/usuario/login/", name="usuarioLogin")
     */
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('usuario/login.html.twig', array(
           'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError()
        ));
    }

    public function cajaLoginAction()
    {
        $helper = $this->get('security.authentication_utils');

        return $this->render('usuario/cajaLogin.html.twig', array(
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError()
        ));
    }

    /**
     * @Route("/usuario/login_check/", name="usuarioLoginCheck")
     */
    public function loginCheckAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/usuario/logout/", name="usuarioLogout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}

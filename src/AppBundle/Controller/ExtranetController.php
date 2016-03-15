<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Oferta;
use AppBundle\Form\Extranet\OfertaType;
use AppBundle\Form\Extranet\TiendaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ExtranetController extends Controller
{
    /**
     * @Route("/extranet/", name="extranetPortada")
     */
    public function portadaAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tienda = $this->get('security.token_storage')->getToken()->getUser();
        $ofertas = $em->getRepository('AppBundle:Tienda')->findOfertasRecientes($tienda->getId());

        return $this->render('extranet/portada.html.twig', array(
            'ofertas' => $ofertas
        ));
    }

    /**
     * @Route("/extranet/oferta/nueva/", name="extranetOfertaNueva")
     */
    public function ofertaNuevaAction(Request $peticion)
    {
        $oferta = new Oferta();
        $formulario = $this->createForm(OfertaType::class, $oferta);

        $formulario->handleRequest($peticion);

        if($formulario->isValid())
        {
            $tienda = $this->get('security.token_storage')->getToken()->getUser();

            $oferta->setCompras(0);
            $oferta->setTienda($tienda);
            $oferta->setCiudad($tienda->getCiudad());
            $oferta->setFechaPublicacion(new \DateTime('now'));
            $oferta->setFechaExpiracion(new \DateTime('now +15 day'));
            $oferta->setRevisada(false);
            $oferta->subirFoto(
                $this->container->getParameter('cupon.directorio.imagenes')
            );
            $em = $this->getDoctrine()->getManager();
            $em->persist($oferta);
            $em->flush();

            return $this->redirectToRoute('extranetPortada');
        }

        return $this->render('extranet/formulario.html.twig', array(
            'accion' => 'crear',
            'formulario' => $formulario->createView()
        ));
    }

    /**
     * @param $id
     * @param Request $peticion
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/extranet/oferta/editar/{id}/", name="extranetOfertaEditar")
     */
    public function ofertaEditarAction($id, Request $peticion)
    {
        $em = $this->getDoctrine()->getManager();
        $oferta = $em->getRepository('AppBundle:Oferta')->find($id);

        if(!$oferta)
        {
            throw $this->createNotFoundException('La oferta no existe');
        }

        if(false === $this->get('security.authorization_checker')->isGranted('ROLE_EDITAR_OFERTA', $oferta))
        {
            throw new AccessDeniedException();
        }

        if($oferta->getRevisada())
        {
            $this->get('session')->getFlashBag()->add('error', 'La oferta no se puede modificar porque ya ha sido
            revisada');

            return $this->redirectToRoute('extranetPortada');
        }

        $formulario = $this->createForm(OfertaType::class, $oferta);

        $rutaFotoOriginal = $formulario->getData()->getRutaFoto();

        $formulario->handleRequest($peticion);

        if($formulario->isValid())
        {
            if($oferta->getFoto() == null)
            {
                $oferta->setRutaFoto($rutaFotoOriginal);
            }
            else
            {
                $directorioFotos = $this->container->getParameter('cupon.directorio.imagenes');
                $oferta->subirFoto($directorioFotos);
                unlink($directorioFotos.$rutaFotoOriginal);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($oferta);
            $em->flush();

            return $this->redirectToRoute('extranetPortada');
        }

        return $this->render('extranet/formulario.html.twig', array(
            'accion' => 'editar',
            'oferta' => $oferta,
            'formulario' => $formulario->createView()
        ));
    }

    /**
     * @param $id
     * @Route("/extranet/ventas/{id}/", name="extranetOfertaVentas")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ofertaVentasAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $ventas = $em->getRepository('AppBundle:Oferta')->findVentasByOferta($id);

        return $this->render('extranet/ventas.html.twig', array(
            'oferta' => $ventas[0]->getOferta(),
            'ventas' => $ventas
        ));
    }

    /**
     * @Route("/extranet/perfil/", name="extranetPerfil")
     */
    public function perfilAction(Request $peticion)
    {
        $tienda = $this->get('security.token_storage')->getToken()->getUser();
        $formulario = $this->createForm('AppBundle\Form\Extranet\TiendaType', $tienda);

        $passwordOriginal = $formulario->getData()->getPassword();

        $formulario->handleRequest($peticion);

        if ($formulario->isValid()) {
            if (null == $tienda->getPassword()) {
                // La tienda no cambia su contraseña, utilizar la original
                $tienda->setPassword($passwordOriginal);
            } else {
                // La tienda cambia su contraseña, codificar su valor
                $encoder = $this->container->get('security.password_encoder');
                $passwordCodificado = $encoder->encodePassword($tienda, $tienda->getPassword());
                $tienda->setPassword($passwordCodificado);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tienda);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info',
                'Los datos de tu perfil se han actualizado correctamente'
            );

            return $this->redirectToRoute('extranetPortada');
        }

        return $this->render('extranet/perfil.html.twig', array(
            'tienda'     => $tienda,
            'formulario' => $formulario->createView()
        ));
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

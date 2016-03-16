<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Usuario;
use AppBundle\Form\Frontend\UsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UsuarioController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{_locale}/usuario/compras/", name="usuarioCompras", defaults={"_locale": "es"}, requirements={"_locale": "es|en"})
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

    /**
     * @param Request $peticion
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{_locale}/usuario/registro/", name="usuarioRegistro", defaults={"_locale": "es"}, requirements={"_locale": "es|en"})
     */
    public function registroAction(Request $peticion)
    {
        $usuario = new Usuario();
        $usuario->setPermiteEmail(true);
        $usuario->setFechaNacimiento(new \DateTime('today - 18 years'));

        $formulario = $this->createForm(UsuarioType::class, $usuario);
        $formulario->handleRequest($peticion);

        if($formulario->isValid())
        {
            $encoder = $this->container->get('security.password_encoder');
            $passwordCodificado = $encoder->encodePassword($usuario, $usuario->getPassword());
            $usuario->setPassword($passwordCodificado);

            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', '¡Enhorabuena! Te has registrado correctamente en Cupon');

            $token = new UsernamePasswordToken(
                $usuario,
                $usuario->getPassword(),
                'frontend',
                $usuario->getRoles()
            );
            $this->container->get('security.token_storage')->setToken($token);
            return $this->redirectToRoute('portada', array(
                'ciudad' => $usuario->getCiudad()->getSlug()
            ));
        }

        return $this->render('usuario/registro.html.twig', array(
            'formulario' => $formulario->createView()
        ));
    }

    /**
     * @Route("/{_locale}/usuario/perfil/", name="usuarioPerfil", defaults={"_locale": "es"}, requirements={"_locale": "es|en"})
     */
    public function perfilAction(Request $peticion)
    {
        $usuario = $this->getUser();
        $formulario = $this->createForm(UsuarioType::class, $usuario);
        $formulario->remove('registrarme')
                   ->add('guardar', SubmitType::class, array('label' => 'Guardar cambios'));
        $formulario->remove('password')
                    ->add('password', RepeatedType::class, array(
                        'type' => PasswordType::class,
                        'required' => false,
                        'invalid_message' => 'Las dos contraseñas deben coincidir',
                        'first_options' => array('label' => 'Contraseña'),
                        'second_options' => array('label' => 'Repite Contraseña'),
                    ));
        $passwordOriginal = $formulario->getData()->getPassword();
        $formulario->handleRequest($peticion);

        if($formulario->isValid())
        {
            if($usuario->getPassword() == null)
            {
                $usuario->setPassword($passwordOriginal);
            }
            else
            {
                $encoder = $this->container->get('security.password_encoder');
                $passwordCodificado = $encoder->encodePassword($usuario, $usuario->getPassword());
                $usuario->setPassword($passwordCodificado);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Los datos de tu perfil se han actualizado correctamente');

            return $this->redirectToRoute('usuarioPerfil');
        }

        return $this->render('usuario/perfil.html.twig', array(
            'usuario' => $usuario,
            'formulario' => $formulario->createView()
        ));
    }
}

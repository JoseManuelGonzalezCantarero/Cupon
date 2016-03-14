<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Routing\Router;

class LoginListener
{
    private $router, $interface, $ciudad = null;

    public function __construct(Router $router, AuthorizationCheckerInterface $interface)
    {
        $this->router = $router;
        $this->interface = $interface;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $this->ciudad = $token->getUser()->getCiudad()->getSlug();
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if(null != $this->ciudad)
        {
            if($this->interface->isGranted('ROLE_TIENDA'))
            {
                $portada = $this->router->generate('extranetPortada');
            }
            else
            {
                $portada = $this->router->generate('portada', array(
                    'ciudad' => $this->ciudad
                ));
            }

            $event->setResponse(new RedirectResponse($portada));
            $event->stopPropagation();
        }
    }
}
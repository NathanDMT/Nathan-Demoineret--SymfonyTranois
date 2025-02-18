<?php

namespace App\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;

class PreAuthenticationListener
{
    private $em;
    private $router;
    private $session;

    public function __construct(EntityManagerInterface $em, RouterInterface $router, SessionInterface $session)
    {
        $this->em = $em;
        $this->router = $router;
        $this->session = $session;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof User && $user->isBlocked()) {
            // Ajouter un message flash
            $this->session->getFlashBag()->add('error', 'Votre compte est bloqué.');

            // Rediriger vers la page de login
            $event->setResponse(new RedirectResponse($this->router->generate('app_login')));

            // Déconnecter l'utilisateur
            $event->getAuthenticationToken()->setAuthenticated(false);
        }
    }
}

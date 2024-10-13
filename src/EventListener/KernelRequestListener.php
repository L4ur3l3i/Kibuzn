<?php

namespace Kibuzn\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class KernelRequestListener
{
    private Security $security;
    private EventDispatcherInterface $dispatcher;

    public function __construct(Security $security, EventDispatcherInterface $dispatcher)
    {
        $this->security = $security;
        $this->dispatcher = $dispatcher;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $user = $this->security->getUser();

        if ($user) {
            // Dispatch the 'user.action' event
            $this->dispatcher->dispatch(new GenericEvent($user), 'user.action');
        }
    }
}

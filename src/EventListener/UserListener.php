<?php 

namespace Kibuzn\EventListener;

use DateTimeImmutable;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\GenericEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UserListener
{
    private EntityManagerInterface $EntityManager;
    private Security $Security;

    public function __construct(EntityManagerInterface $EntityManager, Security $Security)
    {
        $this->EntityManager = $EntityManager;
        $this->Security = $Security;
    }

//    public function onUserRegister(GenericEvent $event): void
//    {
//        // Assuming $event->getSubject() returns a User entity
//        /** @var User $user */
//        $user = $event->getSubject();
//
//        // Perform your database updates, e.g., setting user-specific fields
//        $user->setSomeField('Updated value');
//        $this->EntityManager->persist($user);
//        $this->EntityManager->flush();
//    }

    public function onUserLogin(InteractiveLoginEvent $event): void
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();

        // Update the user as needed, e.g., updating last login time
        $user->setLastLoginAt(new DateTimeImmutable());
        $this->EntityManager->persist($user);
        $this->EntityManager->flush();
    }

    public function onUserAction(GenericEvent $event): void
    {
        /** @var User $user */
        $user = $this->Security->getUser();

        // Perform your database updates, e.g., updating last action time
        $user->setLastActionAt(new DateTimeImmutable());
        $this->EntityManager->persist($user);
        $this->EntityManager->flush();
    }
}

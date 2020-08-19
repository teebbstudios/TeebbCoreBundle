<?php


namespace Teebb\CoreBundle\Subscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Teebb\CoreBundle\Event\UserEvents;
use Teebb\CoreBundle\Utils\Mailer;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SecuritySubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserEvents::SEND_ACTIVE_MESSAGE => 'onSendActiveMessage',
            UserEvents::RESETTING_SEND_MESSAGE => 'onResettingSendMessage'
        ];
    }

    /**
     * @param UserEvents $event
     * @throws \Exception
     * @throws TransportExceptionInterface
     */
    public function onSendActiveMessage(UserEvents $event)
    {
        $user = $event->getUser();
        $this->mailer->sendRegisterActiveMessage($user);
    }

    /**
     * @param UserEvents $event
     * @throws TransportExceptionInterface
     */
    public function onResettingSendMessage(UserEvents $event)
    {
        $user = $event->getUser();

        $this->mailer->sendResettingMessage($user);
    }
}
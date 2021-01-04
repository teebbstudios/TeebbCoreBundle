<?php


namespace Teebb\CoreBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Teebb\CoreBundle\Exception\ApiProblemException;

/**
 * 用于API接口异常的统一返回处理
 */
class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ApiProblemException)
        {
            $apiProblem = $exception->getApiProblem();

            $response = new JsonResponse(
                $apiProblem->toArray(),
                $apiProblem->getStatusCode()
            );
            $response->headers->set('Content-Type', 'application/problem+json');
            $event->setResponse($response);
        }
    }
}
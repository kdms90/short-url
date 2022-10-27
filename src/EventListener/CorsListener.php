<?php


namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class CorsListener
 * @package App\EventListener
 */
class CorsListener implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST   => ['onKernelRequest', 9999],
            KernelEvents::RESPONSE  => ['onKernelResponse', 9999],
            KernelEvents::EXCEPTION => ['onKernelException', 9999],
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        // Don't do anything if it's not the master request.
        if (!$event->getRequestType() == HttpKernelInterface::MAIN_REQUEST) {
            return;
        }
        $response  = $event->getResponse();
        $reqHeader = '*';
        if ($response) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST,PUT,GET,DELETE,OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', '*');
            $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition');
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        // Don't do anything if it's not the master request.
        if (!$event->getRequestType() == HttpKernelInterface::MAIN_REQUEST) {
            return;
        }
        $request = $event->getRequest();
        $method  = $request->getRealMethod();

        if (Request::METHOD_OPTIONS === $method) {
            $response = new Response();
            $event->setResponse($response);
        }
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response  = $event->getResponse();
        if ($response) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST,PUT,GET,DELETE,OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', '*');
            $response->headers->set('Access-Control-Expose-Headers', 'Content-Disposition');
        }
    }
}

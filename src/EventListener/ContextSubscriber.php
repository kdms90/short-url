<?php

namespace App\EventListener;

use App\Controller\AbstractBackController;
use App\Controller\Foundation\Back\CatchExceptionController;
use App\Exception\ContextException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class ContextSubscriber
 *
 * Provide resources for apply specific action when certain event occur.
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App\EventListener
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class ContextSubscriber implements EventSubscriberInterface
{
    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    /**
     * ContextSubscriber constructor.
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     */
    public function onKernelController(ControllerEvent $event)
    {
        /** @var \App\Controller\AbstractFoundationController $controller */
        $controller = $event->getController();
        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }
        if ($controller instanceof AbstractBackController && ($event->getRequestType() == HttpKernel::MASTER_REQUEST)) {
            $controller->loadCurrentContext();
            if (!($controller instanceof CatchExceptionController)) {
                /** @var \App\Entity\User\Access $access */
                $access = $controller->getCurrentUser();
                if ($access && !($access->current_company_id)) {
                    $response = new JsonResponse([], Response::HTTP_UNAUTHORIZED);
                    new ContextException($response->send());
                }
            }
        }
    }
}

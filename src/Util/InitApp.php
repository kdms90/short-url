<?php

namespace App\Util;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Cette classe est éxécutée avant chaque appel d'un controller principal.
 * Elle permet d'initier les valeurs par défaut requise
 *
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    App
 * @subpackage App\Util
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class InitApp
{
    /** @var RequiredContent $requiredContent */
    private $requiredContent;

    /**
     * InitApp constructor.
     *
     * @param RequiredContent $requiredContent
     */
    public function __construct(RequiredContent $requiredContent)
    {
        $this->requiredContent = $requiredContent;
    }

    /**
     * Enable to init database with defaults values
     *
     * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function onKernelController(ControllerEvent $event)
    {
        //On s'assure que nous sommes bien en présence d'une requete principale
        if ($event->getRequestType() !== HttpKernelInterface::MAIN_REQUEST) {
            return;
        } else {
            //Init defaults content
            $this->requiredContent->init();
        }
    }
}

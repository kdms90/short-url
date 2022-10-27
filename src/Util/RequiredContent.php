<?php


namespace App\Util;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;

/**
 * Contient la définition des pages requises par défaut sur la plateforme
 *
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    App
 * @subpackage App\Util
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class RequiredContent
{
    /** @var EntityManager $entityManager */
    private $entityManager;


    /**
     * RequiredContent constructor.
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function init()
    {
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
        }
    }
}

<?php

namespace App\EventListener;

use App\Entity\Sneak;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class EventsSubscriber
 *
 * Provide resources for apply specific action when certain event occur.
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App\EventListener
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
abstract class EventsSubscriber
{
    /** @var EntityManagerInterface */
    protected $entityManager;
    /** @var TranslatorInterface */
    protected $translator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator    = $translator;
    }
}

<?php

namespace App\EventListener;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class OSEvent
 *
 * Defines global handle event in application.
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    App\Handlers
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class AppEvent extends Event
{
    /** @var string */
    private $message;
    /** @var UserInterface */
    private $author;
    /** @var array */
    private $arguments;

    /**
     * @param string $message
     * @param UserInterface $author
     * @param array $arguments
     */
    public function __construct($message, UserInterface $author = null, $arguments = [])
    {
        $this->message   = $message;
        $this->author    = $author;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return \App\Entity\User\Actor
     */
    public function getActor()
    {
        if ($this->getAuthor())
            return $this->getAuthor()->getActor();
        return null;
    }

    /**
     * @return UserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param UserInterface $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }
} 

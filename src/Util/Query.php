<?php

namespace App\Util;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class Query
 * @package App\Util
 */
class Query
{
    /** @var \Doctrine\ORM\EntityManagerInterface $_em */
    private $_em;
    /** @var \App\Util\Tools $_tools */
    private $_tools;

    /**
     * Query constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \App\Util\Tools $tools
     */
    public function __construct(EntityManagerInterface $manager, Tools $tools)
    {
        $this->_em    = $manager;
        $this->_tools = $tools;
    }

    /**
     * @return \App\Util\Tools
     */
    public function tools()
    {
        return $this->_tools;
    }
}

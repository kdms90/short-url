<?php

/**
 * The Custom Twig extension class.
 *
 * Contain generic method for visitors.
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.1.0
 * @package    App
 * @subpackage App\Controller
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */

namespace App\Twig;

use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * The Custom Twig extension class.
 *
 * Permet de savoir si une route exists.
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.1.0
 * @package    App
 * @subpackage App\Twig
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class PathExtension extends AbstractExtension
{
    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    /**
     * PathExtension constructor.
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return array|\Twig\TwigFilter[]
     */
    public function getFilters()
    {
        return [new TwigFilter('routeExists', [$this, 'routeExists'])];
    }

    /**
     * @param $name
     * @return bool
     */
    public function routeExists($name)
    {
        $name = (string)$name;
        return (null === $this->router->getRouteCollection()->get($name)) ? false : true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_path_extension';
    }
}

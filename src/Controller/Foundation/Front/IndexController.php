<?php

namespace App\Controller\Foundation\Front;

use App\Contracts\ControllerInterface;
use App\Controller\AbstractFrontController;


/**
 * Class IndexControllerAbstract
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App\Controller
 * @subpackage App\Controller\Front
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class IndexController extends AbstractFrontController implements ControllerInterface
{    /**
     * Affiche la page accueil MKonnected
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index()
    {
        return $this->render('foundation/front/index/index.html.twig', []);
    }
}

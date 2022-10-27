<?php

namespace App\Controller\Business\Back;

use App\Controller\AbstractBackController;

/**
 * Class IndexController
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App\Controller
 * @subpackage App\Controller\Back
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 */
class IndexController extends AbstractBackController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index()
    {
        //$this->pageTitle = 'Tableau de bord';
        return $this->render( 'foundation/back/index/index.html.twig', []);
    }

    /**
     * Implements this method for init index actions.
     * @param \App\Contracts\FoundationInterface|null $entity
     */
    protected function initHeaderContents(\App\Contracts\FoundationInterface $entity = null)
    {
        // TODO: Implement initHeaderContents() method.
    }
}

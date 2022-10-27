<?php

namespace App\Controller\Foundation\Front;

use App\Controller\AbstractFoundationController;
use App\Entity\Blog\Post;
use App\Entity\Business\About;
use App\Entity\Business\Service;
use App\Entity\CmsPage;
use App\Entity\Parameter;

/**
 * Class IndexController
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App\Controller
 * @subpackage App\Controller\Front
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class EmbedController extends AbstractFoundationController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function defaultMeta()
    {
        return $this->render('foundation/front/embed/default-meta.html.twig', []);
    }
}

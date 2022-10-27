<?php

namespace App\Contracts;

/**
 * The main Contracts class.
 *
 * Enable class to implement addI18n items.
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App
 * @subpackage App\Contracts
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
interface FoundationI18nInterface
{

    /**
     * Return true if entity can be edited
     *
     * @param \App\Entity\AbstractFoundationI18n $item
     * @return \App\Contracts\FoundationInterface
     */
    public function addI18n($item);
}

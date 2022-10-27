<?php

namespace App\Contracts;

/**
 * The main Contracts class.
 *
 * Contains generic method will be implements by all objects managers.
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App
 * @subpackage App\Contracts
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
interface FoundationInterface
{
    /**
     * Return next sequence of entity code
     *
     * @return string
     */
    public static function getSequence();

    /**
     * Return true if entity is deleted and false otherwise
     *
     * @return bool
     */
    public function getDeleted();

    /**
     * Return true if entity can be edited
     *
     * @return bool
     */
    public function isLocked();
}

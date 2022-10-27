<?php

namespace App\Contracts;

/**
 * Interface IAccounting. Interface permettant d'avoir toutes les opérations à comptabiliser.
 *
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App
 * @subpackage App\Contracts
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
interface RoleMemberInterface
{
    //Différents module dévelopés dans l'application.
    const USER_MODULE        = 11;

    /**
     * Retourne un acteur ayant le rôle
     *
     * @return \App\Entity\User\Actor
     */
    public function getMember();
}

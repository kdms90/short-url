<?php

namespace App;

/**
 * Class DBCoreEvents
 *
 * Defines available events in all application.
 *
 * @link       http://github.com/kdms90
 *
 * @since      2.0.0
 * @package    App
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
final class AppEvents
{
    const PRE_PERSIST     = 'prePersist';
    const POST_LOAD       = 'postLoad';
    const PRE_UPDATE      = 'preUpdate';
    const POST_REMOVE     = 'postRemove';
    const OPERATION_OCCUR = 'onOperationOccur'; //Lévé lorsqu'il y a un opération à écrire dans le journal comptable
    const COMPANY_ADDED   = 'onCompanyAdded'; //Lévé lorsqu'une compagnie est créée
}

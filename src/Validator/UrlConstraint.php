<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 *
 * UrlConstraint enable to valide url with specific design
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @subpackage App\Validator
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @Annotation
 */
class UrlConstraint extends Constraint
{
    public $message      = "Url non valide";
    public $length       = 4;
    public $required     = true;
}

<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 *
 * UrlConstraintValidator contain validation logic
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @subpackage App\Validator
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class UrlConstraintValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid phone number.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        $value         = preg_replace('/\s+/', '', $value);
        $isGoodPattern = true;
        $length        = (int)$constraint->length;
        if ($length > strlen($value))
            $isGoodPattern = false;
        if (!$this->isUrl($value) || !$isGoodPattern)
            $this->context->addViolation($constraint->message);
    }

    private function isUrl($uri)
    {
        if (preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}' . '((:[0-9]{1,5})?\\/.*)?$/i', $uri)) {
            return $uri;
        } else {
            return false;
        }
    }
}

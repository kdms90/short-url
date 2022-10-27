<?php


namespace App\Contracts;

/**
 * Interface ITokenGenerator
 * @package App\Contracts
 */
interface TokenGeneratorInterface
{
    /**
     * @param $length
     * @return string
     */
    public function generateToken($length = 32);
}

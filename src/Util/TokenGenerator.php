<?php


namespace App\Util;

use App\Contracts\TokenGeneratorInterface;
use Exception;

/**
 * Class TokenGenerator
 * @package App\Util
 */
class TokenGenerator implements TokenGeneratorInterface
{
    /**
     * @param int $length
     * @return string
     */
    public function generateToken($length = 32)
    {
        try {
            return rtrim(strtr(base64_encode(random_bytes($length)), '+/', '-_'), '=');
        } catch (Exception $e) {
            $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString     = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
    }
}

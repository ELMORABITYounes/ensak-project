<?php
/**
 * Created by PhpStorm.
 * User: ELMORABIT
 * Date: 14/08/2018
 * Time: 14:56
 */

namespace AppBundle\Util;


class TokenGenerator
{
    /**
     * @return string
     */
    public function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
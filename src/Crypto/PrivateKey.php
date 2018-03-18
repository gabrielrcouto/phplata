<?php
namespace PHPlata\Crypto;

class PrivateKey
{
    /**
     * Generates a new private key
     *
     * @return string
     */
    public static function generate():string
    {
        $keypar = sodium_crypto_sign_keypair();
        return sodium_crypto_sign_secretkey($keypar);
    }
}

<?php
namespace PHPlata\Crypto;

class Signature
{
    public static function sign($data, $privateKey):string
    {
        return sodium_crypto_sign($data, $privateKey);
    }
}

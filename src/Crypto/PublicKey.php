<?php
namespace PHPCoin\Crypto;

use StephenHill\Base58;

class PublicKey
{
    const VERSION = '01';

    /**
     * Generates the public key from a private key
     *
     * @param string $privateKey
     * @return string
     */
    public static function generate(string $privateKey):string
    {
        return sodium_crypto_sign_publickey_from_secretkey($privateKey);
    }

    /**
     * Generate the hashed version of public key
     *
     * Based on https://en.bitcoin.it/wiki/Technical_background_of_version_1_Bitcoin_addresses
     * 
     * @param string $publicKey
     * @return string
     */
    public static function generateHash(string $publicKey):string
    {
        $sha256 = hash('sha256', $publicKey);
        $ripemd160 = self::VERSION . hash('ripemd160', $sha256);
        $sha256 = hash('sha256', $ripemd160);
        $sha256 = hash('sha256', $sha256);
        $checksum = substr($sha256, 0, 8);

        $base58 = new Base58();
        return $base58->encode($ripemd160 . $checksum);
    }
}
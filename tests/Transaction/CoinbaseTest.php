<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;
use PHPlata\Transaction\Coinbase;
use PHPlata\Script\PayToPubkeyHashScript;
use PHPlata\Crypto\PrivateKey;
use PHPlata\Crypto\PublicKey;

final class CoinbaseTest extends TestCase
{
    public function testCoinbaseIntegrity()
    {
        $privateKey = PrivateKey::generate();
        $publicKey = PublicKey::generate($privateKey);
        $publicKeyHash = PublicKey::generateHash($publicKey);

        $coinbase = new Coinbase($publicKeyHash);

        $this->assertNotEmpty($coinbase->vin);
        $this->assertNotEmpty($coinbase->vout);
    }
}
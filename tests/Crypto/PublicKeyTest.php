<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;
use PHPlata\Crypto\PrivateKey;
use PHPlata\Crypto\PublicKey;

final class PublicKeyTest extends TestCase
{
    protected $privateKey;

    protected function setup()
    {
        $this->privateKey = PrivateKey::generate();
    }

    public function testGenerate()
    {
        $this->assertNotEmpty(PublicKey::generate($this->privateKey));
    }

    public function testGenerateHash()
    {
        $publicKey = PublicKey::generate($this->privateKey);
        $this->assertNotEmpty(PublicKey::generateHash($publicKey));
    }
}
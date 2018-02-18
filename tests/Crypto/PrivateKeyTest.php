<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;
use PHPCoin\Crypto\PrivateKey;

final class PrivateKeyTest extends TestCase
{
    public function testGenerate()
    {  
        $this->assertNotEmpty(PrivateKey::generate());
    }
}
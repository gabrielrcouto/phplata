<?php
declare (strict_types = 1);

namespace PHPlata\tests\Crypto;

use PHPUnit\Framework\TestCase;
use PHPlata\Crypto\PrivateKey;

final class PrivateKeyTest extends TestCase
{
    public function testGenerate()
    {  
        $this->assertNotEmpty(PrivateKey::generate());
    }
}
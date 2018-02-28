<?php
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;
use PHPlata\Transaction\Coinbase;
use PHPlata\Script\PayToPubkeyHashScript;
use PHPlata\Crypto\PrivateKey;
use PHPlata\Crypto\PublicKey;
use PHPlata\Blockchain\Block;
use PHPlata\Blockchain\Chain;

final class ChainTest extends TestCase
{
    protected $block;

    protected function setup()
    {
        $this->block = new Block();
        $this->block->header->bits ='535f0119';
        $this->block->header->hashMerkleRoot = null;
        $this->block->header->hashPrevBlock = null;
        $this->block->header->nonce = 1;
        $this->block->header->time = time();
        $this->block->header->version = 1;
        
        $this->block->calculateHash();
    }

    public function testGetExistingBlockByHash()
    {
        Chain::addBlock($this->block);

        $this->assertEquals(Chain::getBlockByHash($this->block->hash), $this->block);
    }

    public function testGetNonExistingBlockByHash()
    {
        Chain::addBlock($this->block);

        $this->assertEquals(Chain::getBlockByHash('123'), null);
    }

    public function testGetLeaf()
    {
        Chain::addBlock($this->block);

        // Create a leaf
        $block = new Block();
        $block->header->bits = '535f0119';
        $block->header->hashMerkleRoot = null;
        $block->header->hashPrevBlock = $this->block->hash;
        $block->header->nonce = 1;
        $block->header->time = time();
        $block->header->version = 1;

        $block->calculateHash();

        Chain::addBlock($block);

        $this->assertEquals(Chain::getLeaves(), [$block->hash => $block]);
    }
}
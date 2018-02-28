<?php
namespace PHPlata;

use PHPlata\Blockchain\Block;
use PHPlata\Crypto\PrivateKey;
use PHPlata\Crypto\PublicKey;
use PHPlata\Transaction\Coinbase;
use PHPlata\Blockchain\Chain;

class Local
{
    protected $privateKey;
    protected $publicKey;
    protected $publicKeyHash;

    public function init()
    {
        $this->privateKey = PrivateKey::generate();
        $this->publicKey = PublicKey::generate($this->privateKey);
        $this->publicKeyHash = PublicKey::generateHash($this->publicKey);

        // Create the first block
        $this->createBlock();

        // Create 10 more blocks
        for ($i = 0; $i < 10; $i++) {
            $this->createBlock();
        }

        var_dump(Chain::getBlocks());
    }

    public function createBlock()
    {
        $hashPrevBlock = null;

        $leaves = Chain::getLeaves();

        if (count($leaves) > 0) {
            $hashPrevBlock = (reset($leaves))->hash;
        }
        
        $block = new Block();
        $block->header->bits = '1b00ffff';
        $block->header->hashMerkleRoot = null;
        $block->header->hashPrevBlock = $hashPrevBlock;
        $block->header->nonce = 1;
        $block->header->time = time();
        $block->header->version = 1;

        $coinbase = new Coinbase($this->publicKeyHash);
        $block->addTransaction($coinbase);

        $block->calculateHashMerkleRoot();
        $block->mine();

        echo 'mined' . PHP_EOL;

        Chain::addBlock($block);
    }
}
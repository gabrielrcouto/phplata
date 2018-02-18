<?php
namespace PHPCoin\Blockchain;

use PHPCoin\Block;

class Chain
{
    protected $blocks;

    public function __construct()
    {
        $this->blocks = [];
    }

    public function createBlock($data)
    {
        // Genesis (first) Block previous hash
        $previousBlockHash = '0';

        if (count($this->blocks) > 0) {
            $previousBlockHash = $this->blocks[count($this->blocks) - 1]->getHash();
        }

        $newBlock = new Block([], count($this->blocks) + 1, $previousBlockHash);
        $this->blocks[] = $newBlock;

        return $newBlock;
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    public function getLastBlock()
    {
        if (empty($blocks)) {
            return null;
        }

        return $blocks[count($blocks) - 1];
    }
}

<?php
namespace PHPlata\Blockchain;

use PHPlata\Block;

class Chain
{
    protected static $blocks;

    public function createBlock($data)
    {
        // Genesis (first) Block previous hash
        $previousBlockHash = '0';

        if (count(self::$blocks) > 0) {
            $previousBlockHash = self::$blocks[count(self::$blocks) - 1]->getHash();
        }

        $newBlock = new Block([], count(self::$blocks) + 1, $previousBlockHash);
        self::$blocks[] = $newBlock;

        return $newBlock;
    }

    public static function getBlocks()
    {
        return self::$blocks;
    }

    public static function getLastBlock()
    {
        if (empty($blocks)) {
            return null;
        }

        return $blocks[count($blocks) - 1];
    }
}

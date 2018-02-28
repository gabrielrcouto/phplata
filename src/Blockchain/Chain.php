<?php
namespace PHPlata\Blockchain;

use PHPlata\Blockchain\Block;

class Chain
{
    protected static $blocks = [];
    protected static $leaves = [];

    public static function addBlock(Block $block)
    {
        self::$blocks[$block->hash] = $block;
        self::$leaves[$block->hash] = $block;

        // The block is not a leaf anymore
        if (array_key_exists($block->header->hashPrevBlock, self::$leaves)) {
            unset(self::$leaves[$block->header->hashPrevBlock]);
        }
    }

    public static function getBlockByHash(string $hash): ? Block
    {
        if (array_key_exists($hash, self::$blocks)) {
            return self::$blocks[$hash];
        }

        return null;
    }

    public static function getBlocks()
    {
        return self::$blocks;
    }

    public static function getLeaves()
    {
        return self::$leaves;
    }
}

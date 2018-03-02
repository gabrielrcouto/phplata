<?php
namespace PHPlata\Blockchain;

use PHPlata\Blockchain\Block;
use PHPlata\Transaction\Transaction;

class Chain
{
    protected static $blocks = [];
    protected static $leaves = [];
    protected static $transactions = [];

    public static function addBlock(Block $block)
    {
        self::$blocks[$block->hash] = $block;
        self::$leaves[$block->hash] = $block;

        // The block is not a leaf anymore
        if (array_key_exists($block->header->hashPrevBlock, self::$leaves)) {
            unset(self::$leaves[$block->header->hashPrevBlock]);
        }

        foreach ($block->transactions as $transaction) {
            self::$transactions[$transaction->txid] = $transaction;
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

    public static function getTransactionById($id): ? Transaction
    {
        if (array_key_exists($id, self::$transactions)) {
            return self::$transactions[$id];
        }

        return null;
    }
}

<?php
namespace PHPlata\Blockchain;

use Pleo\Merkle\FixedSizeTree;
use PHPlata\Blockchain\BlockHeader;
use PHPlata\Transaction\Transaction;

class Block
{
    public $hash;
    public $header;
    public $transactions;

    public function __construct()
    {
        $this->header = new BlockHeader();
        $this->transactions = [];
    }

    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;
    }

    /**
     * Calculate the block hash
     *
     * @return void
     */
    public function calculateHash()
    {
        if (! $this->header->hashMerkleRoot) {
            $this->calculateHashMerkleRoot();
        }

        $this->hash = hash('sha256', hash('sha256', json_encode([
            $this->header->version,
            $this->header->hashPrevBlock,
            $this->header->hashMerkleRoot,
            $this->header->time,
            $this->header->bits,
            $this->header->nonce,
        ])));
    }

    /**
     * Calculate the hash of merkle tree
     *
     * @return void
     */
    public function calculateHashMerkleRoot()
    {
        if (count($this->transactions) === 0) {
            return;
        }

        $block = $this;
        $transactionsCount = count($this->transactions);

        // FixedSizeTree doesn't work with only one transaction
        if ($transactionsCount === 1) {
            ++$transactionsCount;
        }

        $hasher = function ($data) {
            return hash('sha256', hash('sha256', json_encode($data)));
        };

        $finished = function ($hash) use ($block) {
            $block->header->hashMerkleRoot = $hash;
        };

        $tree = new FixedSizeTree($transactionsCount, $hasher, $finished);

        for ($i = 0; $i < $transactionsCount; $i++) { 
            if ($i > count($this->transactions) - 1) {
                $tree->set($i, (string) $this->transactions[$i - 1]);
                continue;
            }

            $tree->set($i, (string) $this->transactions[$i]);
        }
    }

    /**
     * Find the block hash which is < than the current difficulty
     *
     * @return void
     */
    public function mine()
    {
        $target = $this->header->getTargetFromBits();

        // Update time
        $this->header->time = time();

        $this->calculateHash();

        while (base_convert($this->hash, 16, 10) > $target) {
            // Increment nonce
            ++$this->header->nonce;
            // Update time
            $this->header->time = time();

            // Re-calculate the hash
            $this->calculateHash();
        }
    }
}

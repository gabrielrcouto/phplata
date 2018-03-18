<?php
declare (strict_types = 1);

namespace PHPlata\Blockchain;

use Pleo\Merkle\FixedSizeTree;
use PHPlata\Transaction\Transaction;

class Block
{
    private $hash;
    private $header;
    private $transactions;

    /**
     * Block constructor.
     * @param BlockHeaderInterface $header
     * @param array $transactionsos
     * @param string|null $hash
     */
    public function __construct(BlockHeaderInterface $header, array $transactionsos = [], string $hash = null)
    {
        $this->header = $header;
        $this->transactions = $transactionsos;
        $this->hash = is_null($hash) ? $this->calculateHash($header) : $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return BlockHeaderInterface
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return array
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;
    }

    /**
     * @param BlockHeaderInterface $header
     * @return string
     */
    public function calculateHash(BlockHeaderInterface $header)
    {
        return hash('sha256', hash('sha256', json_encode([
            $header->getVersion(),
            $header->getHashPrevBlock(),
            $header->getHashMerkleRoot(),
            $header->getTime(),
            $header->getBits(),
            $header->getNonce(),
        ])));
    }

    /**
     * Calculate the hash of merkle tree
     * @return null|string
     */
    public function calculateHashMerkleRoot()
    {
        if (count($this->transactions) === 0) {
            return;
        }
        $hashMerkleRoot = null;
        $transactionsCount = count($this->transactions);
        // FixedSizeTree doesn't work with only one transaction
        if ($transactionsCount === 1) {
            ++$transactionsCount;
        }

        $hasher = function ($data) {
            return hash('sha256', hash('sha256', json_encode($data)));
        };

        $finished = function ($hash) use (&$hashMerkleRoot) {
            $hashMerkleRoot = $hash;
        };
        $tree = new FixedSizeTree($transactionsCount, $hasher, $finished);

        for ($i = 0; $i < $transactionsCount; $i++) {
            if ($i > count($this->transactions) - 1) {
                $tree->set($i, (string) $this->transactions[$i - 1]);
                continue;
            }
            $tree->set($i, (string) $this->transactions[$i]);
        }

        return $hashMerkleRoot;
    }

    /**
     * @TODO move it to another place
     * Find the block hash which is < than the current difficulty
     * @return Block
     */
    public function mine()
    {
        $target = $this->header->getTargetFromBits();
        $header = $this->header;

        // Update time
        $this->header->renewTime();
        $hash = $this->getHash();

        while (base_convert($hash, 16, 10) > $target) {
            $header = BlockHeader::factory(
                $this->header->getBits(),
                $this->calculateHashMerkleRoot(),
                $this->header->getHashPrevBlock(),
                $this->header->increaseNonce(), // Increment nonce
                $this->header->renewTime(), // Update time
                $this->header->getVersion()
            );
            // Re-calculate the hash
            $hash = $this->calculateHash($header);
        }

        return self::factory($header, $this->transactions, $hash);
    }

    /**
     * @param BlockHeaderInterface $header
     * @param array $transactionsos
     * @param string|null $hash
     * @return Block
     */
    public static function factory(BlockHeaderInterface $header, array $transactionsos = [], string $hash = null)
    {
        return new Block($header, $transactionsos, $hash);
    }
}

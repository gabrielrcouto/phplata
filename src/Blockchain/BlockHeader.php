<?php
namespace PHPlata\Blockchain;

class BlockHeader
{
    // Target T for the proof of work problem in compact format.
    public $bits;
    // Top hash of the Merkle tree built from all transactions.
    public $hashMerkleRoot;
    // 256-bit hash of the previous block header 
    public $hashPrevBlock;
    // 32-bit number (starts at 0) 
    public $nonce;
    // Current timestamp as seconds since 1970-01-01T00:00 UTC
    public $time;
    // Block format version
    public $version;

    public function getTargetFromBits()
    {
        // https://bitcoin.stackexchange.com/questions/44579/how-is-a-block-header-hash-compared-to-the-target-bits

        // The first byte is the "exponent"
        $exponent = hexdec(substr($this->bits, 0, 2));

        // The next 3 bytes are the "coefficient"
        $coefficient = hexdec(substr($this->bits, 2));

        return $coefficient * 2 ** (8 * ($exponent - 3));
    }
}
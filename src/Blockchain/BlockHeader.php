<?php
declare (strict_types = 1);

namespace PHPlata\Blockchain;

class BlockHeader implements BlockHeaderInterface
{
    // Target T for the proof of work problem in compact format.
    private $bits;
    // Top hash of the Merkle tree built from all transactions.
    private $hashMerkleRoot;
    // 256-bit hash of the previous block header
    private $hashPrevBlock;
    // 32-bit number (starts at 0)
    private $nonce;
    // Current timestamp as seconds since 1970-01-01T00:00 UTC
    private $time;
    // Block format version
    private $version;

    /**
     * BlockHeader constructor.
     * @param string $bits
     * @param string|null $hashMerkleRoot
     * @param string|null $hashPrevBlock
     * @param int $nonce
     * @param int $time
     * @param int $version
     */
    public function __construct(
        string $bits,
        string $hashMerkleRoot = null,
        string $hashPrevBlock = null,
        int $nonce,
        int $time,
        int $version
    ) {
        $this->bits = $bits;
        $this->hashMerkleRoot = $hashMerkleRoot;
        $this->hashPrevBlock = $hashPrevBlock;
        $this->nonce = $nonce;
        $this->time = $time;
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getTargetFromBits(): int
    {
        // https://bitcoin.stackexchange.com/questions/44579/how-is-a-block-header-hash-compared-to-the-target-bits

        // The first byte is the "exponent"
        $exponent = hexdec(substr($this->bits, 0, 2));

        // The next 3 bytes are the "coefficient"
        $coefficient = hexdec(substr($this->bits, 2));

        return $coefficient * 2 ** (8 * ($exponent - 3));
    }

    /**
     * @return string
     */
    public function getBits(): string
    {
        return $this->bits;
    }

    /**
     * @return null|string
     */
    public function getHashMerkleRoot(): ?string
    {
        return $this->hashMerkleRoot;
    }

    /**
     * @return null|string
     */
    public function getHashPrevBlock(): ?string
    {
        return $this->hashPrevBlock;
    }

    /**
     * @return int
     */
    public function getNonce(): int
    {
        return $this->nonce;
    }

    /**
     * @return int
     */
    public function increaseNonce(): int
    {
        ++$this->nonce;
        return $this->nonce;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function renewTime(): int
    {
        $this->time = time();
        return $this->time;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param string $bits
     * @param string|null $hashMerkleRoot
     * @param string|null $hashPrevBlock
     * @param int $nonce
     * @param int $time
     * @param int $version
     * @return BlockHeader
     */
    public static function factory(
        string $bits,
        string $hashMerkleRoot = null,
        string $hashPrevBlock = null,
        int $nonce,
        int $time,
        int $version
    ) {
        return new BlockHeader($bits, $hashMerkleRoot, $hashPrevBlock, $nonce, $time, $version);
    }
}

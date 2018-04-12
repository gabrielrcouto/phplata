<?php
declare(strict_types = 1);

namespace PHPlata\Blockchain;

interface BlockHeaderInterface
{
    /**
     * Calculate target from bits
     * https://bitcoin.stackexchange.com/questions/44579/how-is-a-block-header-hash-compared-to-the-target-bits
     * @return float
     */
    public function getTargetFromBits(): float;

    /**
     * @return string
     */
    public function getBits(): string;

    /**
     * @return null|string
     */
    public function getHashMerkleRoot(): ?string;

    /**
     * @return null|string
     */
    public function getHashPrevBlock(): ?string;

    /**
     * @return int
     */
    public function getNonce(): int;

    /**
     * @return int
     */
    public function increaseNonce(): int;

    /**
     * @return int
     */
    public function getTime(): int;

    /**
     * @return int
     */
    public function renewTime(): int;

    /**
     * @return int
     */
    public function getVersion(): int;
}

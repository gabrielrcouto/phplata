<?php
namespace PHPCoin\Blockchain;

use PHPCoin\Blockchain\BlockHeader;

class Block
{
    protected $header;
    protected $data;

    public function __construct($header, $data)
    {
        $this->header = new BlockHeader($header);

        $this->data = $data;

        $this->calculateHash();
    }

    public function calculateHash()
    {
        $this->hash = hash('sha256', serialize([
            $this->index,
            $this->data,
            $this->previousBlockHash
        ]));
    }

    public function getHash()
    {
        return $this->hash;
    }
}

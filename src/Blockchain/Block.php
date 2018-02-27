<?php
namespace PHPlata\Blockchain;

use PHPlata\Blockchain\BlockHeader;

class Block
{
    protected $data;
    protected $hash;
    protected $header;

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

<?php
namespace PHPlata\Transaction;

class Txin
{
    public $previousTx;
    public $index;
    public $script;

    public function __construct(string $previousTx, int $index, array $script)
    {
        $this->previousTx = $previousTx;
        $this->index = $index;
        $this->script = $script;
    }

    public function isCoinbase(): bool
    {
        return $this->previousTx === '0000000000000000000000000000000000000000000000000000000000000000';
    }
}

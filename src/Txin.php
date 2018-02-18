<?php
namespace PHPCoin;

class Txin
{
    public $hash;
    public $script;

    public function __construct(string $hash, array $script)
    {
        $this->hash = $hash;
        $this->script = $script;
    }
}
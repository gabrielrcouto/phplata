<?php
namespace PHPlata\Transaction;

class Txout
{
    public $value;
    public $script;

    public function __construct(float $value, string $script)
    {
        $this->value = $value;
        $this->script = $script;
    }
}

<?php
namespace PHPlata\Transaction;

use PHPlata\Transaction\Transaction;

class Validator
{
    public function __invoke(Transaction $transaction):bool
    {
        return $this->isVinValid($vin);
    }

    /**
     * Validate the transaction inputs
     *
     * @return boolean
     */
    protected function isVinValid(array $vin):bool
    {
        foreach ($vin as $txin) {
        }
    }
}

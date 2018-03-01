<?php
namespace PHPlata\Consensus;

use PHPlata\Transaction\Transaction;

class TransactionVerifier
{
    public function checkTransaction(Transaction $transaction):bool
    {
        // Basic checks that don't depend on any context
        if (empty($transaction->vout)) {
            return false;
        }

        $voutTotalValue = 0.0;

        // Check for negative or overflow output values
        foreach ($transaction->vout as $txout) {
            if ($txout->value < 0) {
                return false;
            }

            $voutTotalValue += $txout->value;
        }

        // Check if input transactions exists
        // Check if vin total value < vout total value
    }
}

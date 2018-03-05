<?php
namespace PHPlata\Transaction;

use PHPlata\Transaction\Txin;

class Transaction
{
    public $txid;
    public $version = 1;
    // Input Vector
    public $vin = [];
    // Output Vector
    public $vout = [];

    /**
     * Add a transaction to input transaction array
     */
    public function addToVin(Txin $txin):void
    {
        foreach ($this->vin as $addedTransaction) {
            if ($addedTransaction->previousTx === $txin->previousTx && $addedTransaction->index === $txin->index) {
                //@todo - Create a custom exception class
                throw new \Exception('Transaction already added', 1);
            }
        }

        $this->vin[] = $txin;
    }

    /**
     * Add a transaction to output transaction array
     */
    public function addToVout(Txout $txout):void
    {
        $this->vout[] = $txout;
    }

    /**
     * Calculate the Txid, it's the hash of this transaction object
     */
    public function calculateTxid():void
    {
        $json = json_encode([
            $this->version,
            $this->vin,
            $this->vout
        ]);

        $this->txid = hash('sha256', $json);
    }

    public function __toString()
    {
        return json_encode($this);
    }
}
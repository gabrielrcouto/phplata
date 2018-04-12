<?php
namespace PHPlata\Transaction;

use PHPlata\Transaction\Txin;
use PHPlata\Script\CoinbaseScript;

class Coinbase extends Transaction
{
    const REWARD = 1;

    public function __construct(string $receiverHash)
    {
        $script = CoinbaseScript::getReceiverScript();
        $txIn = new Txin('0000000000000000000000000000000000000000000000000000000000000000', 0, $script);
        $this->addToVin($txIn);

        $script = CoinbaseScript::getSenderScript(($receiverHash));
        $rewardTx = new Txout(self::REWARD, $script);
        $this->addToVout($rewardTx);
    }
}

<?php
namespace PHPlata\Transaction;

use PHPlata\Transaction\Txin;
use PHPlata\Script\PayToPubkeyHashScript;

class Coinbase extends Transaction
{
    const REWARD = 1;

    public function __construct(string $receiverHash)
    {
        $script = PayToPubkeyHashScript::getSenderScript(($receiverHash));
        $rewardTx = new Txout(self::REWARD, $script);
        $this->addToVout($rewardTx);
    }
}
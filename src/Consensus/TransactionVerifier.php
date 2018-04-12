<?php
namespace PHPlata\Consensus;

use PHPlata\Crypto\PublicKey;
use PHPlata\Crypto\Signature;
use PHPlata\Transaction\Transaction;
use PHPlata\Blockchain\Chain;
use PHPlata\Transaction\Txin;
use PHPlata\Transaction\Txout;
use PHPSandbox\PHPSandbox;
use ReflectionFunction;

class TransactionVerifier
{
    /**
     * @param Transaction $transaction
     * @return bool
     * @throws \Exception
     */
    public static function checkTransaction(Transaction $transaction): bool
    {
        // Basic checks that don't depend on any context
        if (empty($transaction->vin)) {
            throw new \Exception('Empty Vin');
        }

        if (empty($transaction->vout)) {
            throw new \Exception('Empty Vout');
        }

        $voutTotalValue = 0.0;

        // Check for negative or overflow output values
        foreach ($transaction->vout as $txout) {
            if ($txout->value < 0) {
                throw new \Exception('Txout with value < 0');
            }

            $voutTotalValue += $txout->value;
        }

        // Check if input transactions exists
        $vinTotalValue = 0.0;

        foreach ($transaction->vin as $txin) {
            if ($txin->isCoinbase()) {
                continue;
            }

            $previousTx = Chain::getTransactionById($txin->previousTx);

            if (! $previousTx) {
                throw new \Exception('PreviousTx not found');
            }

            if ($txin->index > count($previousTx->vout) - 1) {
                throw new \Exception('PreviousTx Vout not found');
            }

            $vinTotalValue += $previousTx->vout[$txin->index]->value;
        }

        // Check if vin total value > vout total value
        if ($voutTotalValue > $vinTotalValue) {
            throw new \Exception('Vout value > Vin value');
        }

        return true;
    }

    /**
     * @param Txin $txin
     * @param Txout $txout
     * @return bool
     * @throws \PHPSandbox\Error
     */
    public static function checkTransactionScript(Txin $txin, Txout $txout): bool
    {
        $args = $txin->script;

        $sandbox = PHPSandbox::create();
        $sandbox->setOption('allow_functions', true);
        $sandbox->defineClass('ReflectionFunction', ReflectionFunction::class);
        $sandbox->defineClass('PublicKey', PublicKey::class);
        $sandbox->defineClass('Signature', Signature::class);
        $sandbox->defineFunc('getTransactionData', function () use ($txin, $txout) {
            return json_encode([$txin, $txout]);
        });
        $sandbox->defineVar('args', $args);

        $sandbox->prepare($txout->script . PHP_EOL .
            '$function = new ReflectionFunction("execute");
            return $function->invokeArgs($args);;
        ');
        
        return $sandbox->execute();
    }
}

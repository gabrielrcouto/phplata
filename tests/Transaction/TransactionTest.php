<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPlata\Transaction\Transaction;
use PHPlata\Transaction\Txin;
use PHPlata\Transaction\Txout;
use PHPlata\Script\PayToPubkeyHashScript;
use PHPlata\Crypto\PrivateKey;
use PHPlata\Crypto\PublicKey;

final class TransactionTest extends TestCase
{
    const DEFAULT_TX_HASH = 'ABC0000000ABC0000000ABC0000000ABC0000000ABC0000000ABC00000001234';
    const DEFAULT_VALUE = 12345.6789;

    protected $receiverScript;
    protected $senderScript;
    protected $transaction;

    protected function setUp()
    {
        $privateKey = PrivateKey::generate();
        $publicKey = PublicKey::generate($privateKey);
        $publicKeyHash = PublicKey::generateHash($publicKey);

        $this->transaction = new Transaction;
        $this->senderScript = PayToPubkeyHashScript::getSenderScript($publicKeyHash);
        $this->receiverScript = PayToPubkeyHashScript::getReceiverScript($publicKey, '');
    }

    public function testAddToVinNotAddedTxin()
    {
        $txin = new Txin(self::DEFAULT_TX_HASH, $this->receiverScript);
        $this->transaction->addToVin($txin);
        $this->assertContains($txin, $this->transaction->vin);
    }

    /**
     * @expectedException Exception
     */
    public function testAddToVinAlreadyAddedTxinException()
    {
        $txin = new Txin(self::DEFAULT_TX_HASH, $this->receiverScript);
        $this->transaction->addToVin($txin);
        $this->transaction->addToVin($txin);
    }

    public function testIfCalculateHashFillsTxid()
    {
        $txin = new Txin(self::DEFAULT_TX_HASH, $this->receiverScript);
        $this->transaction->addToVin($txin);

        $txout = new Txout(self::DEFAULT_VALUE, $this->senderScript);
        $this->transaction->addToVout($txout);

        $this->transaction->calculateTxid();

        $this->assertNotEmpty($this->transaction->txid);
    }
}
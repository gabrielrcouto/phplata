<?php
declare (strict_types = 1);

namespace PHPlata\tests\Consensus;

use PHPlata\Blockchain\BlockHeader;
use PHPUnit\Framework\TestCase;
use PHPlata\Blockchain\Block;
use PHPlata\Blockchain\Chain;
use PHPlata\Transaction\Txin;
use PHPlata\Consensus\TransactionVerifier;
use PHPlata\Crypto\PublicKey;
use PHPlata\Crypto\PrivateKey;
use PHPlata\Script\PayToPubkeyHashScript;
use PHPlata\Transaction\Transaction;
use PHPlata\Transaction\Txout;

final class TransactionVerifierTest extends TestCase
{
    protected $privateKey;
    protected $publicKey;
    protected $publicKeyHash;
    protected $transaction;

    protected function setup()
    {
        $this->privateKey = PrivateKey::generate();
        $this->publicKey = PublicKey::generate($this->privateKey);
        $this->publicKeyHash = PublicKey::generateHash($this->publicKey);

        $this->transaction = new Transaction;
        $script = PayToPubkeyHashScript::getSenderScript($this->publicKeyHash);
        $txout = new Txout(50.99, $script);
        $this->transaction->addToVout($txout);
        $this->transaction->calculateTxid();

        $header = $header = BlockHeader::factory(
            '535f0119',
            null,
            null,
            1,
            time(),
            1
        );
        $block = Block::factory($header);
        $block->addTransaction($this->transaction);
        Chain::addBlock($block);
    }

    public function testCheckTransactionWithValidTransaction()
    {
        $transaction = new Transaction;
        $script = PayToPubkeyHashScript::getReceiverScript($this->publicKey, '');
        $txin = new Txin($this->transaction->txid, 0, $script);

        $script = PayToPubkeyHashScript::getSenderScript($this->publicKeyHash);
        $txout = new Txout(20.99, $script);

        $transaction->addToVin($txin);
        $transaction->addToVout($txout);

        $this->assertTrue(TransactionVerifier::checkTransaction($transaction));
    }

    /**
     * @expectedException Exception
     */
    public function testCheckTransactionWithVoutGreaterThanVin()
    {
        $transaction = new Transaction;
        $script = PayToPubkeyHashScript::getReceiverScript($this->publicKey, '');
        $txin = new Txin($this->transaction->txid, 0, $script);

        $script = PayToPubkeyHashScript::getSenderScript($this->publicKeyHash);
        $txout = new Txout(51.99, $script);

        $transaction->addToVin($txin);
        $transaction->addToVout($txout);

        TransactionVerifier::checkTransaction($transaction);
    }

    /**
     * @expectedException Exception
     */
    public function testCheckTransactionWithoutVin()
    {
        $transaction = new Transaction;

        $script = PayToPubkeyHashScript::getSenderScript($this->publicKeyHash);
        $txout = new Txout(51.99, $script);

        $transaction->addToVout($txout);

        TransactionVerifier::checkTransaction($transaction);
    }

    /**
     * @expectedException Exception
     */
    public function testCheckTransactionWithoutVout()
    {
        $transaction = new Transaction;

        $script = PayToPubkeyHashScript::getReceiverScript($this->publicKey, '');
        $txin = new Txin($this->transaction->txid, 0, $script);

        $transaction->addToVin($txin);

        TransactionVerifier::checkTransaction($transaction);
    }

    /**
     * @expectedException Exception
     */
    public function testCheckTransactionWithValueBelowZero()
    {
        $transaction = new Transaction;
        $script = PayToPubkeyHashScript::getReceiverScript($this->publicKey, '');
        $txin = new Txin($this->transaction->txid, 0, $script);

        $script = PayToPubkeyHashScript::getSenderScript($this->publicKeyHash);
        $txout = new Txout(-1, $script);

        $transaction->addToVin($txin);
        $transaction->addToVout($txout);

        TransactionVerifier::checkTransaction($transaction);
    }

    /**
     * @expectedException Exception
     */
    public function testCheckTransactionWithUnknownTransaction()
    {
        $transaction = new Transaction;
        $script = PayToPubkeyHashScript::getReceiverScript($this->publicKey, '');
        $txin = new Txin('123123123123', 0, $script);

        $script = PayToPubkeyHashScript::getSenderScript($this->publicKeyHash);
        $txout = new Txout(10.99, $script);

        $transaction->addToVin($txin);
        $transaction->addToVout($txout);

        TransactionVerifier::checkTransaction($transaction);
    }

    /**
     * @expectedException Exception
     */
    public function testCheckTransactionWithUnknownTransactionIndex()
    {
        $transaction = new Transaction;
        $script = PayToPubkeyHashScript::getReceiverScript($this->publicKey, '');
        $txin = new Txin($this->transaction->txid, 1, $script);

        $script = PayToPubkeyHashScript::getSenderScript($this->publicKeyHash);
        $txout = new Txout(10.99, $script);

        $transaction->addToVin($txin);
        $transaction->addToVout($txout);

        TransactionVerifier::checkTransaction($transaction);
    }

    public function testCheckTransactionScript()
    {
        $script = PayToPubkeyHashScript::getReceiverScript($this->publicKey, '');
        $txin = new Txin($this->transaction->txid, 1, $script);

        $script = PayToPubkeyHashScript::getSenderScript($this->publicKeyHash);
        $txout = new Txout(10.99, $script);

        $this->assertTrue(TransactionVerifier::checkTransactionScript($txin, $txout));
    }
}
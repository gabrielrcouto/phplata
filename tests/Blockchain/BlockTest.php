<?php
declare (strict_types = 1);

namespace PHPlata\tests\Blockchain;

use PHPlata\Blockchain\Block;
use PHPlata\Blockchain\BlockHeader;
use PHPlata\Transaction\Transaction;
use PHPUnit\Framework\TestCase;

final class BlockTest extends TestCase
{
    public function testCalculateHash()
    {
        $time = 1521343961;
        $header = $header = BlockHeader::factory(
            '535f0119',
            null,
            null,
            1,
            $time,
            1
        );
        $block = Block::factory($header);
        $hash = $block->calculateHash($header);

        $this->assertEquals('f241f40a49a25cee2ab25c84edd18df98cc0fec77adde1d996dfe2bfe0ad004e', $hash);
    }

    public function testCalculateHashWithPrevBlock()
    {
        $time = 1521344143;
        $header = $header = BlockHeader::factory(
            '535f0119',
            null,
            'bffe09efb2f716488443f82b4b49989fb81ca895296804383bb81557daa9196e',
            1,
            $time,
            1
        );
        $block = Block::factory($header);
        $hash = $block->calculateHash($header);

        $this->assertEquals('c19461ededed57e0fd39847e34d83d1a8650a7d577793b177640ea652781740b', $hash);
    }

    public function testCalculateHashWithMerkleRoot()
    {
        $time = 1521344535;
        $header = $header = BlockHeader::factory(
            '535f0119',
            'c973cc38e4eb09629e553415ec4a61f3062ed3be9c1a183f838b18ed8d1ad0a5',
            'bffe09efb2f716488443f82b4b49989fb81ca895296804383bb81557daa9196e',
            1,
            $time,
            1
        );
        $block = Block::factory($header);
        $hash = $block->calculateHash($header);

        $this->assertEquals('aee4cf240c0601eb2b659129dc88a8a53a315799624ddddd94b0871e25ad97f7', $hash);
    }

    public function testCalculateHashWithUpperNonceAndUpperVersion()
    {
        $time = 1521344635;
        $header = $header = BlockHeader::factory(
            '535f0119',
            'c973cc38e4eb09629e553415ec4a61f3062ed3be9c1a183f838b18ed8d1ad0a5',
            'bffe09efb2f716488443f82b4b49989fb81ca895296804383bb81557daa9196e',
            256,
            $time,
            128
        );
        $block = Block::factory($header);
        $hash = $block->calculateHash($header);

        $this->assertEquals('03807f36beed17d8f270e61dd4c91aaaa89d7b023fbcc3136177b138085bfb7e', $hash);
    }

    public function testCalculateHashMerkleRootWithoutTransactions()
    {
        $time = 1521344635;
        $header = $header = BlockHeader::factory(
            '535f0119',
            'c973cc38e4eb09629e553415ec4a61f3062ed3be9c1a183f838b18ed8d1ad0a5',
            'bffe09efb2f716488443f82b4b49989fb81ca895296804383bb81557daa9196e',
            256,
            $time,
            128
        );
        $block = Block::factory($header);

        $this->assertNull($block->calculateHashMerkleRoot());
    }

    public function testCalculateHashMerkleRootWithTransactions()
    {
        $time = 1521344635;
        $header = $header = BlockHeader::factory(
            '535f0119',
            'c973cc38e4eb09629e553415ec4a61f3062ed3be9c1a183f838b18ed8d1ad0a5',
            'bffe09efb2f716488443f82b4b49989fb81ca895296804383bb81557daa9196e',
            256,
            $time,
            128
        );
        $block = Block::factory($header);
        /** @var Transaction $transaction */
        $transaction = $this->getMockBuilder(Transaction::class)
            ->disableOriginalConstructor()
            ->getMock();
        $block->addTransaction($transaction);

        $this->assertEquals(
            '5882b87fb7b32f068e704852fe2a35a9529663212cb836976fffb2f98725d5b6',
            $block->calculateHashMerkleRoot()
        );
    }

    public function testCalculateHashMerkleRootWithMultipleTransactions()
    {
        $time = 1521344635;
        $header = $header = BlockHeader::factory(
            '535f0119',
            'c973cc38e4eb09629e553415ec4a61f3062ed3be9c1a183f838b18ed8d1ad0a5',
            'bffe09efb2f716488443f82b4b49989fb81ca895296804383bb81557daa9196e',
            256,
            $time,
            128
        );
        $block = Block::factory($header);
        /** @var Transaction $transaction */
        $transaction = $this->getMockBuilder(Transaction::class)
            ->disableOriginalConstructor()
            ->getMock();
        $block->addTransaction($transaction);
        $block->addTransaction($transaction);
        $block->addTransaction($transaction);
        $block->addTransaction($transaction);
        $block->addTransaction($transaction);
        $block->addTransaction($transaction);
        $block->addTransaction($transaction);
        $block->addTransaction($transaction);
        $block->addTransaction($transaction);
        $block->addTransaction($transaction);

        $this->assertEquals(
            '5bca90e151a0163230d2aa3957bc1d98de3c4986f94332f08bf25ed6e13ab6be',
            $block->calculateHashMerkleRoot()
        );
    }
}
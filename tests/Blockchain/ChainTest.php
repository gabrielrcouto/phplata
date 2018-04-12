<?php
declare (strict_types = 1);

namespace PHPlata\tests\Blockchain;

use PHPlata\Blockchain\BlockHeader;
use PHPUnit\Framework\TestCase;
use PHPlata\Blockchain\Block;
use PHPlata\Blockchain\Chain;

final class ChainTest extends TestCase
{
    /**
     * @var Block
     */
    protected $block;

    protected function setup()
    {
        $header = BlockHeader::factory(
            '535f0119',
            null,
            null,
            1,
            time(),
            1
        );
        $this->block = Block::factory($header, []);
    }

    public function testGetExistingBlockByHash()
    {
        Chain::addBlock($this->block);

        $this->assertEquals(Chain::getBlockByHash($this->block->getHash()), $this->block);
    }

    public function testGetNonExistingBlockByHash()
    {
        Chain::addBlock($this->block);

        $this->assertEquals(Chain::getBlockByHash('123'), null);
    }

    public function testGetLeaf()
    {
        Chain::addBlock($this->block);

        $header = BlockHeader::factory(
            '535f0119',
            null,
            $this->block->getHash(),
            1,
            time(),
            1
        );
        // Create a leaf
        $block = Block::factory($header);
        Chain::addBlock($block);

        $this->assertEquals(Chain::getLeaves(), [$block->getHash() => $block]);
    }
}
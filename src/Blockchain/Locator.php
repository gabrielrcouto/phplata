<?php
namespace PHPlata\Blockchain;

use PHPlata\Blockchain\Block;
use PHPlata\Blockchain\Chain;

class Locator
{
    public function getBlockByHash(string $hash): ?Block
    {
        $blocks = Chain::getBlocks();

        foreach ($blocks as $block) {
            if ($block->hash === $hash) {
                return $block;
            }
        }

        return null;
    }
}
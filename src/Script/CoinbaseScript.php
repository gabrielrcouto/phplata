<?php
namespace PHPlata\Script;

class CoinbaseScript
{
    /**
     * Get the receiver script part - For txin
     *
     * @return array
     */
    public static function getReceiverScript(): array
    {
        return [];
    }

    /**
     * Get the sender script part - For txout
     *
     * @param string $receiverPublicHash
     * @return string
     */
    public static function getSenderScript(string $receiverPublicHash) : string
    {
        $script = file_get_contents(__DIR__ . '/PayToPubkeyHashScriptSender.php');

        // Replace the %PUBLIC_HASH%
        return str_replace('%PUBLIC_HASH%', $receiverPublicHash, $script);
    }
}

<?php
namespace PHPlata\Script;

class PayToPubkeyHashScript
{
    /**
     * Get the receiver script part
     *
     * @param string $receiverPublicKey
     * @param string $receiverSignature
     * @return string
     */
    public static function getReceiverScript(string $receiverPublicKey, string $receiverSignature): array
    {
        return [$receiverPublicKey, $receiverSignature];
    }

    /**
     * Get the sender script part
     *
     * @param string $receiverPublicHash
     * @return string
     */
    public static function getSenderScript(string $receiverPublicHash):string
    {
        $script = file_get_contents(__DIR__ . '/PayToPubkeyHashScriptSender.php');

        // Replace the %PUBLIC_HASH%
        return str_replace('%PUBLIC_HASH%', $receiverPublicHash, $script);
    }
}

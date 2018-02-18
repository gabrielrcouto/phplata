<?php
function execute(string $receiverPublicKey, string $receiverSignature):bool
{
    if (generatePublicHash($receiverPublicKey) === '%PUBLIC_HASH%' && checkSignature($receiverSignature)) {
        return true;
    }

    return false;
}
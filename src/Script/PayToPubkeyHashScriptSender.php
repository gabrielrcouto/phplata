<?php
function execute(string $receiverPublicKey, string $receiverSignature):bool
{
    if (PublicKey::generateHash($receiverPublicKey) === '%PUBLIC_HASH%'
        && Signature::check(getTransactionData(), $receiverPublicKey, $receiverSignature)
    ) {
        return true;
    }

    return false;
}

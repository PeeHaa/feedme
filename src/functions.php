<?php declare(strict_types=1);

namespace PeeHaa\FeedMe;

function generateUuid(): string
{
    $randomData = random_bytes(16);

    $randomData[6] = chr(ord($randomData[6]) & 0x0f | 0x40);
    $randomData[8] = chr(ord($randomData[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($randomData), 4));
}

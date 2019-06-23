<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Validator\Request\Register;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Uuid\Version4;

final class Id implements Rule
{
    /**
     * @param mixed $value
     * @return Promise<Result>
     */
    public function validate($value): Promise
    {
        return (new Version4())->validate($value['id']);
    }
}

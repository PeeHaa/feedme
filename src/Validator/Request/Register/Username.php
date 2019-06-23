<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Validator\Request\Register;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Email\NativeEmailAddress;
use HarmonyIO\Validation\Rule\Rule;

final class Username implements Rule
{
    /**
     * @param mixed $value
     * @return Promise<Result>
     */
    public function validate($value): Promise
    {
        return (new NativeEmailAddress())->validate($value['data']['username']);
    }
}

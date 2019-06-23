<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Validator\Request\LogIn;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\MinimumLength;

final class Password implements Rule
{
    /**
     * @param mixed $value
     * @return Promise<Result>
     */
    public function validate($value): Promise
    {
        return (new MinimumLength(6))->validate($value['data']['password']);
    }
}

<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Validator\Request\Register;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Text\MinimumLength;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Password implements Rule
{
    /**
     * @param mixed $value
     * @return Promise<Result>
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new MinimumLength(6))->validate($value['data']['password']);

            if (!$result->isValid()) {
                return $result;
            }

            if ($value['data']['password'] === $value['data']['password2']) {
                return succeed();
            }

            return fail('Password.NoMatch');
        });
    }
}

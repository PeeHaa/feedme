<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Validator\Text;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class ExactMatch implements Rule
{
    /** @var string */
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * @param mixed $value
     * @return Promise<Result>
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if ($this->text !== $value) {
                return fail('Text.ExactMatch');
            }

            return succeed();
        });
    }
}

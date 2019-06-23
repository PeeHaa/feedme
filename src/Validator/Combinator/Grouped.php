<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Validator\Combinator;

use Amp\Promise;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Grouped implements Rule
{
    /** @var array<string,Rule> */
    private $rules;

    /**
     * @param array<string,Rule> $rules
     */
    public function __construct(array $rules)
    {
        foreach ($rules as $name => $rule) {
            if (!$rule instanceof Rule) {
                $type = gettype($rule);

                if ($type === 'object') {
                    $type = get_class($rule);
                }

                throw new \TypeError(
                    sprintf('Rules may only contain instances of %s, %s given for rule %s', Rule::class, $type, $name),
                );
            }
        }

        $this->rules = $rules;
    }

    /**
     * @param mixed $value
     * @return Promise<Result>
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            $errors = [];

            /** @var Rule $rule */
            foreach ($this->rules as $name => $rule) {
                /** @var Result $result */
                $result = yield $rule->validate($value);

                if ($result->isValid()) {
                    continue;
                }

                $errors[$name] = $result->getErrors();
            }

            if (!$errors) {
                return succeed();
            }

            return fail('Combinator.Grouped', new Parameter('errors', $errors));
        });
    }
}

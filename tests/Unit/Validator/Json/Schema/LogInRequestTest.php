<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Json\Schema;

use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Validator\Json\Schema\LogInRequest;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class LogInRequestTest extends TestCase
{
    /** @var array<mixed> */
    private $validRequestData;

    public function setUp(): void
    {
        $this->validRequestData = [
            'id'   => 'TheId',
            'type' => 'LogIn',
            'data' => [
                'username' => 'TheUsername',
                'password' => 'ThePassword',
            ],
        ];
    }

    public function testValidateFailsOnMissingId(): void
    {
        unset($this->validRequestData['id']);

        /** @var Result $result */
        $result = wait((new LogInRequest())->validate(json_encode($this->validRequestData)));

        $this->assertFalse($result->isValid());
    }

    public function testValidateFailsOnMissingType(): void
    {
        unset($this->validRequestData['type']);

        /** @var Result $result */
        $result = wait((new LogInRequest())->validate(json_encode($this->validRequestData)));

        $this->assertFalse($result->isValid());
    }

    public function testValidateFailsOnMissingData(): void
    {
        unset($this->validRequestData['data']);

        /** @var Result $result */
        $result = wait((new LogInRequest())->validate(json_encode($this->validRequestData)));

        $this->assertFalse($result->isValid());
    }

    public function testValidateFailsOnMissingUsername(): void
    {
        unset($this->validRequestData['data']['username']);

        /** @var Result $result */
        $result = wait((new LogInRequest())->validate(json_encode($this->validRequestData)));

        $this->assertFalse($result->isValid());
    }

    public function testValidateFailsOnMissingPassword(): void
    {
        unset($this->validRequestData['data']['password']);

        /** @var Result $result */
        $result = wait((new LogInRequest())->validate(json_encode($this->validRequestData)));

        $this->assertFalse($result->isValid());
    }

    public function testValidateSucceedsOnValidData(): void
    {
        /** @var Result $result */
        $result = wait((new LogInRequest())->validate(json_encode($this->validRequestData)));

        $this->assertTrue($result->isValid());
    }
}

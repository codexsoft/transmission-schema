<?php

namespace CodexSoft\Transmission\Schema;

use CodexSoft\Transmission\Schema\Elements\AbstractElement;
use PHPUnit\Framework\TestCase;

abstract class AbstractElementTest extends TestCase
{
    protected const EXPECT_VIOLATIONS = true;

    abstract public function dataProviderNormalizeData(): array;

    /**
     * @dataProvider dataProviderNormalizeData
     *
     * @param $input
     * @param AbstractElement $schema
     * @param mixed|null $expectedOutput
     *
     * @param string|null $exceptionClass
     */
    public function _testNormalizeData($input, AbstractElement $schema, $expectedOutput, ?string $exceptionClass): void
    {
        try {
            $normalizedData = $schema->normalizeData($input);
        } catch (\Throwable $e) {
            if ($exceptionClass) {
                self::assertInstanceOf($exceptionClass, $e);
            } else {
                throw new \RuntimeException('Unexpected exception for data '.\var_export($input, true).': '.$e);
            }

            return;
        }

        if ($exceptionClass) {
            throw new \RuntimeException('Expected exception for data '.\var_export($input, true).' '.$exceptionClass.' was not thrown');
        }

        self::assertSame($expectedOutput, $normalizedData);
    }

    /**
     * @dataProvider dataProviderNormalizeData
     *
     * @param $input
     * @param AbstractElement $schema
     * @param mixed|null $expectedOutput
     *
     * @param string|null $violationsExpected
     */
    public function testNormalizeData($input, AbstractElement $schema, $expectedOutput, ?bool $violationsExpected): void
    {
        $result = $schema->validateNormalizedData($input);

        self::assertEquals((bool) $violationsExpected, $result->getViolations()->count() > 0);
        if ($result->getViolations()->count() === 0) {
            self::assertSame($expectedOutput, $result->getData());
        }
    }
}

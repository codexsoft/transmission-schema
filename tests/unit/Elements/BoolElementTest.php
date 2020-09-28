<?php

namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Accept;
use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;
use PHPUnit\Framework\TestCase;

class BoolElementTest extends TestCase
{

    //public function testCompileToSymfonyValidatorConstraint()
    //{
    //}

    public function dataProviderNormalizeData(): array
    {
        $boolean = Accept::bool();
        $nullableBoolean = Accept::bool()->nullable();
        $nullableStrictBoolean = Accept::bool()->nullable()->strict();
        $strictBoolean = Accept::bool()->strict();

        return [
            [[], $boolean, false, IncompatibleInputDataTypeException::class],
            [0.0, $boolean, false, null],
            [1.42, $boolean, true, null],
            ['null', $boolean, true, null],
            ['false', $boolean, true, null],
            ['1', $boolean, true, null],
            ['0', $boolean, false, null],
            ['', $boolean, false, null],
            [null, $boolean, false, null],

            [null, $nullableBoolean, null, null],
            ['', $nullableBoolean, false, null],
            [-1, $nullableBoolean, true, null],
            [1, $nullableBoolean, true, null],
            [0, $nullableBoolean, false, null],
            [[], $nullableBoolean, false, IncompatibleInputDataTypeException::class],

            [1.52, $nullableStrictBoolean, false, IncompatibleInputDataTypeException::class],
            ['', $nullableStrictBoolean, false, IncompatibleInputDataTypeException::class],
            [0, $nullableStrictBoolean, false, IncompatibleInputDataTypeException::class],
            [false, $nullableStrictBoolean, false, null],
            [true, $nullableStrictBoolean, true, null],
            [null, $nullableStrictBoolean, null, null],
            [[], $nullableStrictBoolean, null, IncompatibleInputDataTypeException::class],

            [null, $strictBoolean, null, IncompatibleInputDataTypeException::class],
            [null, $strictBoolean, null, IncompatibleInputDataTypeException::class],
            [true, $strictBoolean, true, null],
            [false, $strictBoolean, false, null],
            [0, $strictBoolean, false, IncompatibleInputDataTypeException::class],
            [1, $strictBoolean, true, IncompatibleInputDataTypeException::class],
            [1.5, $strictBoolean, true, IncompatibleInputDataTypeException::class],
            [0.0, $strictBoolean, false, IncompatibleInputDataTypeException::class],
            ['1', $strictBoolean, false, IncompatibleInputDataTypeException::class],
            ['true', $strictBoolean, false, IncompatibleInputDataTypeException::class],
            ['false', $strictBoolean, false, IncompatibleInputDataTypeException::class],
            [[], $strictBoolean, false, IncompatibleInputDataTypeException::class],
        ];
    }

    /**
     * @dataProvider dataProviderNormalizeData
     *
     * @param $input
     * @param BoolElement $schema
     * @param bool|null $expectedOutput
     *
     * @param string|null $exceptionClass
     */
    public function testNormalizeData($input, BoolElement $schema, ?bool $expectedOutput, ?string $exceptionClass)
    {
        try {
            $normalizedData = $schema->normalizeData($input);
            self::assertSame($expectedOutput, $normalizedData);
        } catch (\Throwable $e) {
            if ($exceptionClass) {
                self::assertInstanceOf($exceptionClass, $e);
            } else {
                throw new \RuntimeException('Unexpected exception for data '.\var_export($input, true).' '.$e);
            }

            return;
        }

        //self::expectException()

        if ($exceptionClass) {
            throw new \RuntimeException('Expected exception for data '.\var_export($input, true).' '.$exceptionClass.' was not thrown');
        }
    }
}

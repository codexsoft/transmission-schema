<?php

namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\AbstractElementTest;
use CodexSoft\Transmission\Schema\Accept;
use CodexSoft\Transmission\Schema\Exceptions\IncompatibleInputDataTypeException;

class IntegerElementTest extends AbstractElementTest
{
    public function dataProviderNormalizeData(): array
    {
        $integer = Accept::integer();
        $nullableInteger = Accept::integer()->nullable();
        $nullableStrictInteger = Accept::integer()->nullable()->strict();
        $strictInteger = Accept::integer()->strict();

        return [
            [[], $integer, null, IncompatibleInputDataTypeException::class],
            [0.0, $integer, 0, null],
            [1.42, $integer, 1, null],
            [1.6, $integer, 1, null],
            [2.4, $integer, 2, null],
            ['null', $integer, 0, null],
            ['false', $integer, 0, null],
            ['1', $integer, 1, null],
            ['0', $integer, 0, null],
            ['', $integer, 0, null],
            [null, $integer, 0, null],

            [null, $nullableInteger, null, null],
            ['', $nullableInteger, 0, null],
            [-1, $nullableInteger, -1, null],
            [1, $nullableInteger, 1, null],
            [0, $nullableInteger, 0, null],
            [[], $nullableInteger, null, IncompatibleInputDataTypeException::class],

            [1.52, $nullableStrictInteger, null, IncompatibleInputDataTypeException::class],
            ['', $nullableStrictInteger, null, IncompatibleInputDataTypeException::class],
            [0, $nullableStrictInteger, 0, null],
            [false, $nullableStrictInteger, null, IncompatibleInputDataTypeException::class],
            [true, $nullableStrictInteger, null, IncompatibleInputDataTypeException::class],
            [null, $nullableStrictInteger, null, null],
            [[], $nullableStrictInteger, null, IncompatibleInputDataTypeException::class],

            [null, $strictInteger, null, IncompatibleInputDataTypeException::class],
            [true, $strictInteger, null, IncompatibleInputDataTypeException::class],
            [false, $strictInteger, null, IncompatibleInputDataTypeException::class],
            [0, $strictInteger, 0, null],
            [1, $strictInteger, 1, null],
            [1.5, $strictInteger, null, IncompatibleInputDataTypeException::class],
            [0.0, $strictInteger, null, IncompatibleInputDataTypeException::class],
            ['1', $strictInteger, null, IncompatibleInputDataTypeException::class],
            ['true', $strictInteger, null, IncompatibleInputDataTypeException::class],
            ['false', $strictInteger, null, IncompatibleInputDataTypeException::class],
            [[], $strictInteger, null, IncompatibleInputDataTypeException::class],
        ];
    }
}

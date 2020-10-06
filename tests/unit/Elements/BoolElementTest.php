<?php

namespace CodexSoft\TransmissionSchema\Elements;

use CodexSoft\TransmissionSchema\AbstractElementTest;
use CodexSoft\TransmissionSchema\Accept;
use CodexSoft\TransmissionSchema\Exceptions\IncompatibleInputDataTypeException;

class BoolElementTest extends AbstractElementTest
{

    public function dataProviderNormalizeData(): array
    {
        $boolean = Accept::bool();
        $nullableBoolean = Accept::bool()->nullable();
        $nullableStrictBoolean = Accept::bool()->nullable()->strict();
        $strictBoolean = Accept::bool()->strict();

        return [
            [[], $boolean, null, IncompatibleInputDataTypeException::class],
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
            [[], $nullableBoolean, null, IncompatibleInputDataTypeException::class],

            [1.52, $nullableStrictBoolean, null, IncompatibleInputDataTypeException::class],
            ['', $nullableStrictBoolean, null, IncompatibleInputDataTypeException::class],
            [0, $nullableStrictBoolean, null, IncompatibleInputDataTypeException::class],
            [false, $nullableStrictBoolean, false, null],
            [true, $nullableStrictBoolean, true, null],
            [null, $nullableStrictBoolean, null, null],
            [[], $nullableStrictBoolean, null, IncompatibleInputDataTypeException::class],

            [null, $strictBoolean, null, IncompatibleInputDataTypeException::class],
            [null, $strictBoolean, null, IncompatibleInputDataTypeException::class],
            [true, $strictBoolean, true, null],
            [false, $strictBoolean, false, null],
            [0, $strictBoolean, null, IncompatibleInputDataTypeException::class],
            [1, $strictBoolean, null, IncompatibleInputDataTypeException::class],
            [1.5, $strictBoolean, null, IncompatibleInputDataTypeException::class],
            [0.0, $strictBoolean, null, IncompatibleInputDataTypeException::class],
            ['1', $strictBoolean, null, IncompatibleInputDataTypeException::class],
            ['true', $strictBoolean, null, IncompatibleInputDataTypeException::class],
            ['false', $strictBoolean, null, IncompatibleInputDataTypeException::class],
            [[], $strictBoolean, null, IncompatibleInputDataTypeException::class],
        ];
    }
}

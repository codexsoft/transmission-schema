<?php

namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\AbstractElementTest;
use CodexSoft\Transmission\Accept;
use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;

class StringElementTest extends AbstractElementTest
{
    public function dataProviderNormalizeData(): array
    {
        $string = Accept::string();
        $stringNullable = Accept::string()->nullable();
        $stringStrict = Accept::string()->strict();
        $stringStrictNullable = Accept::string()->strict()->nullable();

        return [
            ['', $string, '', null],
            ['abc', $string, 'abc', null],
            [1, $string, '1', null],
            [0, $string, '0', null],
            [null, $string, '', null],
            [-1, $string, '-1', null],
            [1.5, $string, '1.5', null],
            [[], $string, null, IncompatibleInputDataTypeException::class],

            ['', $stringNullable, '', null],
            ['abc', $stringNullable, 'abc', null],
            [1, $stringNullable, '1', null],
            [0, $stringNullable, '0', null],
            [null, $stringNullable, null, null],
            [-1, $stringNullable, '-1', null],
            [1.5, $stringNullable, '1.5', null],
            [[], $stringNullable, null, IncompatibleInputDataTypeException::class],

            ['', $stringStrict, '', null],
            ['abc', $stringStrict, 'abc', null],
            [1, $stringStrict, null, IncompatibleInputDataTypeException::class],
            [0, $stringStrict, null, IncompatibleInputDataTypeException::class],
            [null, $stringStrict, null, IncompatibleInputDataTypeException::class],
            [-1, $stringStrict, null, IncompatibleInputDataTypeException::class],
            [1.5, $stringStrict, null, IncompatibleInputDataTypeException::class],
            [[], $stringStrict, null, IncompatibleInputDataTypeException::class],

            ['', $stringStrictNullable, '', null],
            ['abc', $stringStrictNullable, 'abc', null],
            [1, $stringStrictNullable, null, IncompatibleInputDataTypeException::class],
            [0, $stringStrictNullable, null, IncompatibleInputDataTypeException::class],
            [null, $stringStrictNullable, null, null],
            [-1, $stringStrictNullable, null, IncompatibleInputDataTypeException::class],
            [1.5, $stringStrictNullable, null, IncompatibleInputDataTypeException::class],
            [[], $stringStrictNullable, null, IncompatibleInputDataTypeException::class],

            [' abc ', $string->trim(), 'abc', null],
            [' <b>abc</b> ', $string->trim()->stripTags(), 'abc', null],
            [' <script>alert(\'Hello!\')</script> ', $string->trim()->stripTags(), 'alert(\'Hello!\')', null],
        ];
    }
}

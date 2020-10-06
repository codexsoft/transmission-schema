<?php

namespace CodexSoft\TransmissionSchema\Elements;

use CodexSoft\TransmissionSchema\AbstractElementTest;
use CodexSoft\TransmissionSchema\Accept;
use CodexSoft\TransmissionSchema\Exceptions\IncompatibleInputDataTypeException;

class StringElementTest extends AbstractElementTest
{
    public function dataProviderValidateData(): array
    {
        return [
            ['12', Accept::string()->alnum(), true],
            [' 12  ', Accept::string()->alnum(), false],
            [' 12_  ', Accept::string()->alnum(), false],
            ['12_', Accept::string()->alnum(), false],
            [' 12  ', Accept::string()->trim()->minLength(3), false],
            [' 12  ', Accept::string()->minLength(3), true],
            ['12', Accept::string()->minLength(3), false],
            ['123', Accept::string()->minLength(3), true],
            ['123456', Accept::string()->maxLength(5), false],
            ['12345', Accept::string()->maxLength(5), true],
            ['  123456', Accept::string()->maxLength(5), false],
            ['  ', Accept::string()->notBlank(), true],
            ['  ', Accept::string()->trim()->notBlank(), false],
            [' abc ', Accept::string()->trim()->noWhiteSpace(), true],
            ['abc', Accept::string()->noWhiteSpace(), true],
            ['a b c', Accept::string()->noWhiteSpace(), false],
        ];
    }

    /**
     * @dataProvider dataProviderValidateData
     *
     * @param $input
     * @param AbstractElement $schema
     * @param bool $expectedIsValid
     *
     * @throws IncompatibleInputDataTypeException
     */
    public function testValidateData($input, AbstractElement $schema, bool $expectedIsValid): void
    {
        $validationResult = $schema->getValidatedNormalizedData($input);
        //foreach ($validationResult->getViolations() as $violation) {
        //    var_dump($violation->getPropertyPath().': '.$violation->getMessage());
        //}
        self::assertEquals($validationResult->getViolations()->count() === 0, $expectedIsValid);
    }

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

<?php

namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\AbstractElementTest;
use CodexSoft\Transmission\Schema\Accept;

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
            ['abc', Accept::string()->alnum(), true],
            ['abc_', Accept::string()->alnum(), false],
            [' abc', Accept::string()->alnum(), false],
            ['abc', Accept::string()->pattern('/^[a-z]+$/'), true],
            ['abc', Accept::string()->pattern('/^[A-Z]+$/'), false],
            ['abc1', Accept::string()->pattern('/^[a-z]+$/'), false],
        ];
    }

    /**
     * @dataProvider dataProviderValidateData
     *
     * @param $input
     * @param BasicElement $schema
     * @param bool $expectedIsValid
     *
     * @throws IncompatibleInputDataTypeException
     */
    public function testValidateData($input, BasicElement $schema, bool $expectedIsValid): void
    {
        $validationResult = $schema->validateNormalizedData($input);
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
            [null, $string, '', self::EXPECT_VIOLATIONS],
            [-1, $string, '-1', null],
            [1.5, $string, '1.5', null],
            [[], $string, null, self::EXPECT_VIOLATIONS],

            ['', $stringNullable, '', null],
            ['abc', $stringNullable, 'abc', null],
            [1, $stringNullable, '1', null],
            [0, $stringNullable, '0', null],
            [null, $stringNullable, null, null],
            [-1, $stringNullable, '-1', null],
            [1.5, $stringNullable, '1.5', null],
            [[], $stringNullable, null, self::EXPECT_VIOLATIONS],

            ['', $stringStrict, '', null],
            ['abc', $stringStrict, 'abc', null],
            [1, $stringStrict, null, self::EXPECT_VIOLATIONS],
            [0, $stringStrict, null, self::EXPECT_VIOLATIONS],
            [null, $stringStrict, null, self::EXPECT_VIOLATIONS],
            [-1, $stringStrict, null, self::EXPECT_VIOLATIONS],
            [1.5, $stringStrict, null, self::EXPECT_VIOLATIONS],
            [[], $stringStrict, null, self::EXPECT_VIOLATIONS],

            ['', $stringStrictNullable, '', null],
            ['abc', $stringStrictNullable, 'abc', null],
            [1, $stringStrictNullable, null, self::EXPECT_VIOLATIONS],
            [0, $stringStrictNullable, null, self::EXPECT_VIOLATIONS],
            [null, $stringStrictNullable, null, null],
            [-1, $stringStrictNullable, null, self::EXPECT_VIOLATIONS],
            [1.5, $stringStrictNullable, null, self::EXPECT_VIOLATIONS],
            [[], $stringStrictNullable, null, self::EXPECT_VIOLATIONS],

            [' abc ', $string->trim(), 'abc', null],
            [' <b>abc</b> ', $string->trim()->stripTags(), 'abc', null],
            [' <script>alert(\'Hello!\')</script> ', $string->trim()->stripTags(), 'alert(\'Hello!\')', null],
        ];
    }
}

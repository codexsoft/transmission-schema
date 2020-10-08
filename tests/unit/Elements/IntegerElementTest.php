<?php

namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\AbstractElementTest;
use CodexSoft\Transmission\Schema\Accept;

class IntegerElementTest extends AbstractElementTest
{
    public function dataProviderNormalizeData(): array
    {
        $integer = Accept::integer();
        $nullableInteger = Accept::integer()->nullable();
        $nullableStrictInteger = Accept::integer()->nullable()->strict();
        $strictInteger = Accept::integer()->strict();

        return [
            [[], $integer, null, self::EXPECT_VIOLATIONS], // should be scalar
            [0.0, $integer, 0, null],
            [1.42, $integer, 1, null],
            [1.6, $integer, 1, null],
            [2.4, $integer, 2, null],
            ['null', $integer, 0, self::EXPECT_VIOLATIONS], // should be numeric
            ['false', $integer, 0, self::EXPECT_VIOLATIONS], // should be numeric
            ['1', $integer, 1, null],
            ['0', $integer, 0, null],
            ['', $integer, 0, self::EXPECT_VIOLATIONS], // should be numeric
            [null, $integer, 0, self::EXPECT_VIOLATIONS], // should not be null

            [null, $nullableInteger, null, null],
            ['', $nullableInteger, 0, self::EXPECT_VIOLATIONS], // should be numeric
            [-1, $nullableInteger, -1, null],
            [1, $nullableInteger, 1, null],
            [0, $nullableInteger, 0, null],
            [[], $nullableInteger, null, self::EXPECT_VIOLATIONS],

            [1.52, $nullableStrictInteger, null, self::EXPECT_VIOLATIONS],
            ['', $nullableStrictInteger, null, self::EXPECT_VIOLATIONS],
            [0, $nullableStrictInteger, 0, null],
            [false, $nullableStrictInteger, null, self::EXPECT_VIOLATIONS],
            [true, $nullableStrictInteger, null, self::EXPECT_VIOLATIONS],
            [null, $nullableStrictInteger, null, null],
            [[], $nullableStrictInteger, null, self::EXPECT_VIOLATIONS],

            [null, $strictInteger, null, self::EXPECT_VIOLATIONS],
            [true, $strictInteger, null, self::EXPECT_VIOLATIONS],
            [false, $strictInteger, null, self::EXPECT_VIOLATIONS],
            [0, $strictInteger, 0, null],
            [1, $strictInteger, 1, null],
            [1.5, $strictInteger, null, self::EXPECT_VIOLATIONS],
            [0.0, $strictInteger, null, self::EXPECT_VIOLATIONS],
            ['1', $strictInteger, null, self::EXPECT_VIOLATIONS],
            ['true', $strictInteger, null, self::EXPECT_VIOLATIONS],
            ['false', $strictInteger, null, self::EXPECT_VIOLATIONS],
            [[], $strictInteger, null, self::EXPECT_VIOLATIONS],
        ];
    }
}

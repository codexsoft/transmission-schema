<?php

namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\AbstractElementTest;
use CodexSoft\Transmission\Schema\Accept;

class BoolElementTest extends AbstractElementTest
{

    public function dataProviderNormalizeData(): array
    {
        $boolean = Accept::bool();
        $nullableBoolean = Accept::bool()->nullable();
        $nullableStrictBoolean = Accept::bool()->nullable()->strict();
        $strictBoolean = Accept::bool()->strict();

        return [
            [[], $boolean, null, self::EXPECT_VIOLATIONS],
            [0.0, $boolean, false, null],
            [1.42, $boolean, true, null],
            ['null', $boolean, true, null],
            ['false', $boolean, true, null],
            ['1', $boolean, true, null],
            ['0', $boolean, false, null],
            ['', $boolean, false, null],
            [null, $boolean, false, self::EXPECT_VIOLATIONS],

            [null, $nullableBoolean, null, null],
            ['', $nullableBoolean, false, null],
            [-1, $nullableBoolean, true, null],
            [1, $nullableBoolean, true, null],
            [0, $nullableBoolean, false, null],
            [[], $nullableBoolean, null, self::EXPECT_VIOLATIONS],

            [1.52, $nullableStrictBoolean, null, self::EXPECT_VIOLATIONS],
            ['', $nullableStrictBoolean, null, self::EXPECT_VIOLATIONS],
            [0, $nullableStrictBoolean, null, self::EXPECT_VIOLATIONS],
            [false, $nullableStrictBoolean, false, null],
            [true, $nullableStrictBoolean, true, null],
            [null, $nullableStrictBoolean, null, null],
            [[], $nullableStrictBoolean, null, self::EXPECT_VIOLATIONS],

            [null, $strictBoolean, null, self::EXPECT_VIOLATIONS],
            [null, $strictBoolean, null, self::EXPECT_VIOLATIONS],
            [true, $strictBoolean, true, null],
            [false, $strictBoolean, false, null],
            [0, $strictBoolean, null, self::EXPECT_VIOLATIONS],
            [1, $strictBoolean, null, self::EXPECT_VIOLATIONS],
            [1.5, $strictBoolean, null, self::EXPECT_VIOLATIONS],
            [0.0, $strictBoolean, null, self::EXPECT_VIOLATIONS],
            ['1', $strictBoolean, null, self::EXPECT_VIOLATIONS],
            ['true', $strictBoolean, null, self::EXPECT_VIOLATIONS],
            ['false', $strictBoolean, null, self::EXPECT_VIOLATIONS],
            [[], $strictBoolean, null, self::EXPECT_VIOLATIONS],
        ];
    }
}

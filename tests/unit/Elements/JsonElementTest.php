<?php

namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\AbstractElementTest;
use CodexSoft\Transmission\Schema\Accept;
use CodexSoft\Transmission\Schema\Elements\CollectionElementTest\TestData;
use CodexSoft\Transmission\Schema\Exceptions\IncompatibleInputDataTypeException;

class JsonElementTest extends AbstractElementTest
{

    public function dataProviderNormalizeData(): array
    {
        //return [
        //    [null, Accept::json(TestData::class), null, self::EXPECT_VIOLATIONS],
        //];

        return [

            [null, Accept::json(TestData::class)->nullable(), null, null],
            [null, Accept::json(TestData::class), null, self::EXPECT_VIOLATIONS], // should not be null
            [null, Accept::json(TestData::class)->nullable(), null, null],
            [[], Accept::json(TestData::class), [], self::EXPECT_VIOLATIONS], // missed field a

            [
                ['a' => 'hello'],
                Accept::json(['a' => Accept::string()]),
                ['a' => 'hello'],
                null
            ],

            [
                ['a' => 'hello'],
                Accept::json(TestData::class),
                ['a' => 'hello'],
                null
            ],

        ];
    }
}

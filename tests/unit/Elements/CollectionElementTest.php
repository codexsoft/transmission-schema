<?php

namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\AbstractElementTest;
use CodexSoft\Transmission\Schema\Accept;
use CodexSoft\Transmission\Schema\Elements\CollectionElementTest\TestData;
use CodexSoft\Transmission\Schema\Exceptions\IncompatibleInputDataTypeException;

class CollectionElementTest extends AbstractElementTest
{

    public function dataProviderNormalizeData(): array
    {
        $stringCollection = Accept::collection(Accept::string());
        $stringCollectionNullable = Accept::collection(Accept::string())->nullable();

        return [
            [[], $stringCollection, [], null],
            [['a'], $stringCollection, ['a'], null],
            [[1], $stringCollection, ['1'], null],
            [[1.5], $stringCollection, ['1.5'], null],
            [[null], $stringCollection, [''], self::EXPECT_VIOLATIONS], // because element is not nullable
            [null, $stringCollection, null, self::EXPECT_VIOLATIONS],
            ['a', $stringCollection, null, self::EXPECT_VIOLATIONS],
            [1, $stringCollection, null, self::EXPECT_VIOLATIONS],
            [0.5, $stringCollection, null, self::EXPECT_VIOLATIONS],

            [null, $stringCollectionNullable, null, null],

            [
                [['a' => 'hello',]],
                Accept::collection(Accept::json(['a' => Accept::string(),])),
                [['a' => 'hello',]],
                null
            ],

            [
                [['a' => 'hello',], ['a' => 'world',]],
                Accept::collection(Accept::json(TestData::class)),
                [['a' => 'hello',], ['a' => 'world',]],
                null
            ],

            [
                [['a' => 'hello',], ['a' => 42,]],
                Accept::collection(Accept::json(TestData::class)),
                [['a' => 'hello',], ['a' => '42',]],
                null
            ],
        ];
    }
}

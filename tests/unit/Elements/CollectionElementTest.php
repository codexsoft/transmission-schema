<?php

namespace CodexSoft\TransmissionSchema\Elements;

use CodexSoft\TransmissionSchema\AbstractElementTest;
use CodexSoft\TransmissionSchema\Accept;
use CodexSoft\TransmissionSchema\Elements\CollectionElementTest\TestData;
use CodexSoft\TransmissionSchema\Exceptions\IncompatibleInputDataTypeException;

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
            [[null], $stringCollection, [''], null],
            [null, $stringCollection, null, IncompatibleInputDataTypeException::class],
            ['a', $stringCollection, null, IncompatibleInputDataTypeException::class],
            [1, $stringCollection, null, IncompatibleInputDataTypeException::class],
            [0.5, $stringCollection, null, IncompatibleInputDataTypeException::class],

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

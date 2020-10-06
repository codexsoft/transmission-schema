<?php

namespace CodexSoft\TransmissionSchema\Elements;

use CodexSoft\TransmissionSchema\AbstractElementTest;
use CodexSoft\TransmissionSchema\Accept;
use CodexSoft\TransmissionSchema\Elements\CollectionElementTest\TestData;
use CodexSoft\TransmissionSchema\Exceptions\IncompatibleInputDataTypeException;

class JsonElementTest extends AbstractElementTest
{

    public function dataProviderNormalizeData(): array
    {
        return [

            [null, Accept::json(TestData::class), null, IncompatibleInputDataTypeException::class],
            [null, Accept::json(TestData::class)->nullable(), null, null],

            /**
             * Data normalizer does not care about missing fields. Validator does.
             */
            [[], Accept::json(TestData::class), [], null],

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

<?php

namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\AbstractElementTest;
use CodexSoft\Transmission\Accept;
use CodexSoft\Transmission\Elements\CollectionElementTest\TestData;
use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;

class JsonElementTest extends AbstractElementTest
{

    public function dataProviderNormalizeData(): array
    {
        return [

            [null, Accept::json(TestData::class), null, IncompatibleInputDataTypeException::class],
            [null, Accept::json(TestData::class)->nullable(), null, null],
            //[null, Accept::json(TestData::class), null, null],

            // Unexpected exception for data NULL:
            // TypeError: Argument 1 passed to CodexSoft\Transmission\Elements\JsonElement::normalizeDataReturningNormalizedAndExtraData()
            // must be of the type array, null given, called in /home/kozubskiy/code/github/codexsoft/transmission/src/Elements/JsonElement.php on line 158
            // and defined in /home/kozubskiy/code/github/codexsoft/transmission/src/Elements/JsonElement.php:83

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

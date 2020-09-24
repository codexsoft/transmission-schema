<?php

namespace CodexSoft\Transmission;

use CodexSoft\Transmission\AbstractElementTest\EmailStruct;
use PHPUnit\Framework\TestCase;

class AbstractElementTest extends TestCase
{
    public function testNormalizeData()
    {
        $testData = [
            [null, Accept::bool()->strict()->nullable(), null],
            [null, Accept::bool()->strict(), false],
            [123, Accept::text(), '123'],
            [['a', 'b', 42], Accept::collection(Accept::string()->choices(['a', 'b', '42'])), ['a', 'b', '42']],
        ];

        foreach ($testData as [$input, $schema, $expectedNormalized]) {
            (new SchemaChecker($schema))->expectNormalizedData($expectedNormalized)->check($input);
        }

        //$this->expectException()

        (new SchemaChecker(Accept::json(EmailStruct::class)))
            ->expectSfViolations([
                '[message]' => 'This field is missing.',
                '[title]' => 'This field is missing.',
                '[recipients]' => 'This field is missing.',
            ])
            ->expectNormalizedData([
                //'xxx' => 42,
            ])
            ->expectExtraData([
                'xxx' => 42,
            ])
            ->check([
                //'xxx' => '42',
                'xxx' => 42,
            ]);
    }
}

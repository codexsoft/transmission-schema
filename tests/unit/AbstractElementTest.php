<?php

namespace CodexSoft\Transmission;

use CodexSoft\Transmission\AbstractElementTest\EmailStruct;
use CodexSoft\Transmission\Elements\AbstractElement;
use PHPUnit\Framework\TestCase;

abstract class AbstractElementTest extends TestCase
{
    abstract public function dataProviderNormalizeData(): array;

    /**
     * @dataProvider dataProviderNormalizeData
     *
     * @param $input
     * @param AbstractElement $schema
     * @param mixed|null $expectedOutput
     *
     * @param string|null $exceptionClass
     */
    public function testNormalizeData($input, AbstractElement $schema, $expectedOutput, ?string $exceptionClass): void
    {
        try {
            $normalizedData = $schema->normalizeData($input);
        } catch (\Throwable $e) {
            if ($exceptionClass) {
                self::assertInstanceOf($exceptionClass, $e);
            } else {
                throw new \RuntimeException('Unexpected exception for data '.\var_export($input, true).': '.$e);
            }

            return;
        }

        if ($exceptionClass) {
            throw new \RuntimeException('Expected exception for data '.\var_export($input, true).' '.$exceptionClass.' was not thrown');
        }

        self::assertSame($expectedOutput, $normalizedData);
    }

    public function xxxtestNormalizeData()
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

<?php

namespace CodexSoft\Transmission\Schema;

use CodexSoft\Transmission\Schema\AbstractElementTest\EmailStruct;
use CodexSoft\Transmission\Schema\Elements\AbstractElement;
use CodexSoft\Transmission\Schema\Tools\SchemaChecker;
use PHPUnit\Framework\TestCase;

abstract class AbstractElementTest extends TestCase
{
    protected const EXPECT_VIOLATIONS = true;

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
    public function _testNormalizeData($input, AbstractElement $schema, $expectedOutput, ?string $exceptionClass): void
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

    /**
     * @dataProvider dataProviderNormalizeData
     *
     * @param $input
     * @param AbstractElement $schema
     * @param mixed|null $expectedOutput
     *
     * @param string|null $violationsExpected
     */
    public function testNormalizeData($input, AbstractElement $schema, $expectedOutput, ?bool $violationsExpected): void
    {
        //$normalizedData = $schema->normalizeData($input);
        $result = $schema->getValidatedNormalizedData($input);

        if ($input === ' 12  ') {
            $a = (bool) $violationsExpected;
            $b = $result->getViolations()->count() > 0;
            $x=1;
        }
        self::assertEquals((bool) $violationsExpected, $result->getViolations()->count() > 0);
        if ($result->getViolations()->count() === 0) {
            $actualData = $result->getData();
            //if ($expectedOutput !== $actualData) {
            //    $x=1;
            //}
            try {
                self::assertSame($expectedOutput, $result->getData());
            } catch (\Throwable $e) {
                $x=1;
            }

        }

        //self::assertSame($expectedOutput, $result->getData());

        //if ($result->getViolations()->count())
        //
        //try {
        //    $normalizedData = $schema->normalizeData($input);
        //} catch (\Throwable $e) {
        //    if ($violationsExpected) {
        //        self::assertInstanceOf($violationsExpected, $e);
        //    } else {
        //        throw new \RuntimeException('Unexpected exception for data '.\var_export($input, true).': '.$e);
        //    }
        //
        //    return;
        //}
        //
        //if ($violationsExpected) {
        //    throw new \RuntimeException('Expected exception for data '.\var_export($input, true).' '.$violationsExpected.' was not thrown');
        //}
        //
        //self::assertSame($expectedOutput, $normalizedData);
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

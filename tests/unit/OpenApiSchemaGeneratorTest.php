<?php

namespace CodexSoft\Transmission;

use PHPUnit\Framework\TestCase;

class OpenApiSchemaGeneratorTest extends TestCase
{

    public function testGenerateOpenApiV2()
    {
        (new OpenApiSchemaGenerator())->generateOpenApiV2();
    }
}

<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface OpenApiAwareInterface
{
    public function toOpenApiSchema(): array;
    public function toOpenApiParameter(string $name, ?string $in = null): array;
}

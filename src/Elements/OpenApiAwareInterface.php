<?php


namespace CodexSoft\Transmission\Schema\Elements;

/**
 * @deprecated
 */
interface OpenApiAwareInterface
{

    /**
     * @deprecated
     * @return array
     */
    public function toOpenApiSchema(): array;

    /**
     * @deprecated
     * @param string $name
     * @param string|null $in
     *
     * @return array
     */
    public function toOpenApiParameter(string $name, ?string $in = null): array;
}

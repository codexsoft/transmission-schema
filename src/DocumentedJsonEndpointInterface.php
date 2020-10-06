<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\AbstractElement;

interface DocumentedJsonEndpointInterface extends OpenApiV2EndpointInterface
{
    /**
     * Expected request JSON schema
     * @return AbstractElement[]
     */
    public static function bodyInputSchema(): array;

    /**
     * Successful response JSON schema
     * @return AbstractElement[]
     */
    public static function bodyOutputSchema(): array;

    public static function allAlternativeResponses(): array;
}

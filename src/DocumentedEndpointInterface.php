<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\AbstractElement;

interface DocumentedEndpointInterface
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

    public static function getOpenApiTags(): array;

    public static function allAlternativeResponses(): array;

    // todo: cookies?
    // todo: headers?
}

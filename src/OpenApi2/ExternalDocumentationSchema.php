<?php


namespace CodexSoft\Transmission\OpenApi2;


class ExternalDocumentationSchema
{
    /**
     * A short description of the target documentation. GFM syntax can be used for rich text representation.
     */
    public ?string $description = null;

    /**
     * The URL for the target documentation.
     */
    public ?string $url = null;
}

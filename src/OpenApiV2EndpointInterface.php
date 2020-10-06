<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\AbstractElement;

interface OpenApiV2EndpointInterface
{
    public static function getOpenApiSummary(): string;
    public static function getOpenApiDescription(): string;

    /**
     * @return string[]
     */
    public static function getOpenApiProduces(): array;

    /**
     * @return string[]
     */
    public static function getOpenApiConsumes(): array;

    public static function getOpenApiTags(): array;

    /**
     * Expected request query parameters
     * @return AbstractElement[]
     */
    public static function queryParametersSchema(): array;

    /**
     * Expected request path parameters
     * @return AbstractElement[]
     */
    public static function pathParametersSchema(): array;

    /**
     * Expected request body parameters
     * @return AbstractElement[]
     */
    public static function bodyParametersSchema(): array;

    /**
     * Expected request body parameters
     * @return AbstractElement[]
     */
    public static function headerParametersSchema(): array;

    /**
     * Expected request body parameters
     * @return AbstractElement[]
     */
    public static function formDataParametersSchema(): array;

    public static function getResponses(): array;

    // todo: cookies?
    // todo: headers?
}

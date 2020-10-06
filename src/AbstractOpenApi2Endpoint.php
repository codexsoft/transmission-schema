<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\AbstractElement;
use CodexSoft\Transmission\Schemas\InvalidRequestBodySchema;
use Symfony\Component\HttpFoundation\Response;

class AbstractOpenApi2Endpoint
{
    public static function getOpenApiTags(): array
    {
        return [];
    }

    /**
     * Successful response JSON schema
     * @return AbstractElement[]
     */
    abstract public static function bodyOutputSchema(): array;

    public static function getOpenApiSummary(): string
    {
        return '';
    }

    public static function getOpenApiDescription(): string
    {
        return '';
    }

    public static function getOpenApiProduces(): array
    {
        return ['application/json'];
    }

    public static function getOpenApiConsumes(): array
    {
        return ['application/json'];
    }

    public static function queryParametersSchema(): array
    {
        return [];
    }

    public static function pathParametersSchema(): array
    {
        return [];
    }

    public static function bodyParametersSchema(): array
    {
        return [];
    }

    public static function headerParametersSchema(): array
    {
        return [];
    }

    public static function formDataParametersSchema(): array
    {
        return [];
    }

    public static function getResponses(): array
    {
        return [];
    }

    protected static function defaultAlternativeResponses(): array
    {
        return [
            Response::HTTP_BAD_REQUEST => InvalidRequestBodySchema::class,
        ];
    }

    public static function allAlternativeResponses(): array
    {
        return \array_replace(static::defaultAlternativeResponses(), static::alternativeResponses());
    }

    public static function alternativeResponses(): array
    {
        return [];
    }
}

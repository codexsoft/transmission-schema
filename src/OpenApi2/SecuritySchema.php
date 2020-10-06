<?php


namespace CodexSoft\Transmission\OpenApi2;


class SecuritySchema
{
    /**
     * The key into Swagger->securityDefinitions array.
     */
    public ?string $securityDefinition = null;

    /**
     * The type of the security scheme.
     */
    public ?string $type = null;

    /**
     * A short description for security scheme.
     */
    public ?string $description = null;

    /**
     * The name of the header or query parameter to be used.
     */
    public ?string $name = null;

    /**
     * Required The location of the API key.
     */
    public ?string $in = null;

    /**
     * The flow used by the OAuth2 security scheme.
     * @var
     */
    public $flow;

    /**
     * The authorization URL to be used for this flow. This SHOULD be in the form of a URL.
     */
    public ?string $authorizationUrl = null;

    /**
     * The token URL to be used for this flow. This SHOULD be in the form of a URL.
     */
    public ?string $tokenUrl = null;

    /**
     * The available scopes for the OAuth2 security scheme.
     * @var mixed
     */
    public $scopes;
}

<?php


namespace CodexSoft\Transmission\OpenApi2;


class DocumentSchema
{
    /**
     * Specifies the Swagger Specification version being used. It can be used by the Swagger UI and other clients to interpret the API listing.
     * @var string
     */
    public $swagger = '2.0';

    /**
     * Provides metadata about the API. The metadata can be used by the clients if needed.
     * @var Info
     */
    public $info;

    /**
     * The host (name or ip) serving the API. This MUST be the host only and does not include the scheme nor sub-paths. It MAY include a port. If the host is not included, the host serving the documentation is to be used (including the port). The host does not support path templating.
     * @var string
     */
    public $host;

    /**
     * The base path on which the API is served, which is relative to the host. If it is not included, the API is served directly under the host. The value MUST start with a leading slash (/). The basePath does not support path templating.
     * @var string
     */
    public $basePath;

    /**
     * The transfer protocol of the API. Values MUST be from the list: "http", "https", "ws", "wss". If the schemes is not included, the default scheme to be used is the one used to access the specification.
     * @var array
     */
    public $schemes;

    /**
     * A list of MIME types the APIs can consume. This is global to all APIs but can be overridden on specific API calls. Value MUST be as described under Mime Types.
     * @var array
     */
    public $consumes;

    /**
     * A list of MIME types the APIs can produce. This is global to all APIs but can be overridden on specific API calls. Value MUST be as described under Mime Types.
     * @var array
     */
    public $produces;

    /**
     * The available paths and operations for the API.
     * @var Path[]
     */
    public $paths = [];

    /**
     * An object to hold data types produced and consumed by operations.
     * @var Definition[]
     */
    public $definitions = [];

    /**
     * An object to hold parameters that can be used across operations. This property does not define global parameters for all operations.
     * @var Parameter[]
     */
    public $parameters;

    /**
     * An object to hold responses that can be used across operations. This property does not define global responses for all operations.
     * @var Response[]
     */
    public $responses;

    /**
     * Security scheme definitions that can be used across the specification.
     * @var SecurityScheme[]
     */
    public $securityDefinitions;

    /**
     * A declaration of which security schemes are applied for the API as a whole. The list of values describes alternative security schemes that can be used (that is, there is a logical OR between the security requirements). Individual operations can override this definition.
     * @var array
     */
    public $security;

    /**
     * A list of tags used by the specification with additional metadata. The order of the tags can be used to reflect on their order by the parsing tools. Not all tags that are used by the Operation Object must be declared. The tags that are not declared may be organized randomly or based on the tools' logic. Each tag name in the list MUST be unique.
     * @var Tag[]
     */
    public $tags;

    /**
     * Additional external documentation.
     * @var ExternalDocumentation
     */
    public $externalDocs;

    public function generate()
    {
        return [
            "swagger" => "2.0",
            "info" => [
                "title" => "GyperPIM API",
                "description" => "\u0418\u043d\u0442\u0435\u0440\u0444\u0435\u0439\u0441 \u043f\u0440\u043e\u0433\u0440\u0430\u043c\u043c\u043d\u043e\u0433\u043e \u0432\u0437\u0430\u0438\u043c\u043e\u0434\u0435\u0439\u0441\u0442\u0432\u0438\u044f \u0441 \u0431\u0430\u0437\u043e\u0439 \u0434\u0430\u043d\u043d\u044b\u0445 \u043f\u0440\u043e\u0434\u0443\u043a\u0442\u043e\u0432.<br /><a href='https://api.pim.one/static/360-view/'>\u0418\u043d\u0441\u0442\u0440\u0443\u043a\u0446\u0438\u044f \u043a\u0430\u043a \u0434\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u043d\u0430 \u0441\u0432\u043e\u0439 \u0441\u0430\u0439\u0442 \u043f\u0440\u043e\u0441\u043c\u043e\u0442\u0440 \u043f\u0440\u043e\u0434\u0443\u043a\u0442\u0430 \u0432 360</a>",
                "version" => "1.0.0",
            ],
            "host" => "api.pim.one",
            "basePath" => "",
            "schemes" => [
                "https"
            ],
            "consumes" => ["application/json"],
            "produces" => ["application/json"],
            "paths" => [],
            "definitions" => [],
            "parameters" => [],
            "responses" => [],
        ];
    }
}

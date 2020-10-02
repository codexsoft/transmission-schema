<?php


namespace CodexSoft\Transmission\OpenApi2;


use CodexSoft\Transmission\OpenApi2;

class Operation
{

    /**
     * key in the Swagger "Paths Object" for this operation
     */
    public string $path = '';

    /**
     * Key in the Swagger "Path Item Object" for this operation.
     * Allowed values: 'get', 'post', put', 'delete', 'options', 'head' and 'patch'
     */
    public string $method = '';

    /**
     * A list of tags for API documentation control. Tags can be used for logical grouping of operations by resources or any other qualifier.
     */
    public StringCollection $tags;

    /**
     * A short summary of what the operation does. For maximum readability in the swagger-ui, this field SHOULD be less than 120 characters.
     */
    public string $summary = '';

    /**
     * A verbose explanation of the operation behavior. GFM syntax can be used for rich text representation.
     */
    public string $description = '';

    /**
     * Additional external documentation for this operation.
     * @var ExternalDocumentation
     */
    public $externalDocs;

    /**
     * A friendly name for the operation.
     * The id MUST be unique among all operations described in the API.
     * Tools and libraries MAY use the operation id to uniquely identify an operation.
     */
    public string $operationId = OpenApi2::UNDEFINED;

    /**
     * A list of MIME types the operation can consume.
     * This overrides the [consumes](#swaggerConsumes) definition at the Swagger Object.
     * An empty value MAY be used to clear the global definition.
     * Value MUST be as described under Mime Types.
     * @var string[]
     */
    public array $consumes = [];

    /**
     * A list of MIME types the operation can produce.
     * This overrides the [produces](#swaggerProduces) definition at the Swagger Object.
     * An empty value MAY be used to clear the global definition.
     * Value MUST be as described under Mime Types.
     * @var string[]
     */
    public array $produces = [];

    /**
     * A list of parameters that are applicable for this operation.
     * If a parameter is already defined at the Path Item, the new definition will override it, but can never remove it. The list MUST NOT include duplicated parameters. A unique parameter is defined by a combination of a name and location.
     * The list can use the Reference Object to link to parameters that are defined at the Swagger Object's parameters.
     * There can be one "body" parameter at most.
     * @var Parameter[]
     */
    public array $parameters = [];

    /**
     * The list of possible responses as they are returned from executing this operation.
     */
    public ResponseCollection $responses;

    /**
     * The transfer protocol for the operation.
     * Values MUST be from the list: "http", "https", "ws", "wss".
     * The value overrides the Swagger Object schemes definition.
     * @var string[]
     */
    public array $schemes = [];

    /**
     * Declares this operation to be deprecated.
     * Usage of the declared operation should be refrained. Default value is false.
     */
    public bool $deprecated = false;

    /**
     * A declaration of which security schemes are applied for this operation.
     * The list of values describes alternative security schemes that can be used (that is, there is a logical OR between the security requirements).
     * This definition overrides any declared top-level security.
     * To remove a top-level security declaration, an empty array can be used.
     * @var array
     */
    public $security;

    public function __construct()
    {
        $this->responses = new ResponseCollection();
        $this->tags = new StringCollection();
    }

    public function toArray(): array
    {
        $responsesData = [];
        foreach ($this->responses as $response) {
            $responsesData[] = $response->toArray();
        }

        return [
            'tags' => $this->tags->toArray(),
            'parameters' => $inputJsonParameter,
            'responses' => $responsesData,
        ];
    }
}

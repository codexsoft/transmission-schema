<?php


namespace CodexSoft\Transmission\OpenApi2;


class DocumentSchema implements AbstractOpenApiSchemaInterface
{
    /**
     * Specifies the Swagger Specification version being used. It can be used by the Swagger UI and other clients to interpret the API listing.
     */
    public string $swagger = '2.0';

    /**
     * Provides metadata about the API. The metadata can be used by the clients if needed.
     */
    public InfoSchema $info;

    /**
     * The host (name or ip) serving the API. This MUST be the host only and does not include the scheme nor sub-paths. It MAY include a port. If the host is not included, the host serving the documentation is to be used (including the port). The host does not support path templating.
     */
    public ?string $host = null;

    /**
     * The base path on which the API is served, which is relative to the host. If it is not included, the API is served directly under the host. The value MUST start with a leading slash (/). The basePath does not support path templating.
     */
    public ?string $basepath = null;

    /**
     * The transfer protocol of the API. Values MUST be from the list: "http", "https", "ws", "wss". If the schemes is not included, the default scheme to be used is the one used to access the specification.
     */
    public StringCollection $schemes;

    /**
     * A list of MIME types the APIs can consume. This is global to all APIs but can be overridden on specific API calls. Value MUST be as described under Mime Types.
     */
    public StringCollection $consumes;

    /**
     * A list of MIME types the APIs can produce. This is global to all APIs but can be overridden on specific API calls. Value MUST be as described under Mime Types.
     */
    public StringCollection $produces;

    /**
     * The available paths and operations for the API.
     */
    public PathCollection $paths;

    /**
     * An object to hold data types produced and consumed by operations.
     * @var Definition[]
     */
    public $definitions = [];

    /**
     * An object to hold parameters that can be used across operations. This property does not define global parameters for all operations.
     */
    public ParameterCollection $parameters;

    /**
     * An object to hold responses that can be used across operations. This property does not define global responses for all operations.
     */
    public ResponseCollection $responses;

    /**
     * Security scheme definitions that can be used across the specification.
     * @var SecuritySchema[]
     */
    public $securityDefinitions = [];

    /**
     * A declaration of which security schemes are applied for the API as a whole. The list of values describes alternative security schemes that can be used (that is, there is a logical OR between the security requirements). Individual operations can override this definition.
     * @var array
     */
    public $security;

    /**
     * A list of tags used by the specification with additional metadata. The order of the tags can be used to reflect on their order by the parsing tools. Not all tags that are used by the Operation Object must be declared. The tags that are not declared may be organized randomly or based on the tools' logic. Each tag name in the list MUST be unique.
     */
    public StringCollection $tags;

    /**
     * Additional external documentation.
     */
    public ExternalDocumentationSchema $externalDocs;

    public function __construct()
    {
        $this->info = new InfoSchema();
        $this->schemes = new StringCollection();
        $this->consumes = new StringCollection();
        $this->produces = new StringCollection();
        $this->parameters = new ParameterCollection();
        $this->responses = new ResponseCollection();
        $this->tags = new StringCollection();
        $this->externalDocs = new ExternalDocumentationSchema();
        $this->paths = new PathCollection();
    }

    public function exportToArray(): array
    {
        $paths = [];
        foreach ($this->paths->toArray() as $path => $pathOperations) {
            $paths[$path] = $pathOperations->post->toArray();
        }

        return [
            "swagger" => $this->swagger,
            "info" => $this->info->exportToArray(),
            "host" => $this->host,
            "basePath" => $this->basepath,
            "schemes" => $this->schemes->toArray(),
            "consumes" => $this->consumes->toArray(),
            "produces" => $this->produces->toArray(),
            "paths" => $paths,
            "definitions" => [],
            "parameters" => [],
            "responses" => [],
        ];
    }
}

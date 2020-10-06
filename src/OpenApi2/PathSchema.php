<?php


namespace CodexSoft\Transmission\OpenApi2;


class PathSchema implements AbstractOpenApiSchemaInterface
{
    /**
     * $ref See http://json-schema.org/latest/json-schema-core.html#rfc.section.7
     * @var string
     */
    public $ref;

    /**
     * key in the Swagger "Paths Object" for this path.
     */
    public ?string $path = null;

    /**
     * A definition of a GET operation on this path.
     */
    public ?OperationSchema $get = null;

    /**
     * A definition of a PUT operation on this path.
     */
    public ?OperationSchema $put = null;

    /**
     * A definition of a POST operation on this path.
     */
    public ?PostOperationSchema $post = null;

    /**
     * A definition of a DELETE operation on this path.
     */
    public ?OperationSchema $delete = null;

    /**
     * A definition of a OPTIONS operation on this path.
     */
    public ?OperationSchema $options = null;

    /**
     * A definition of a HEAD operation on this path.
     */
    public ?OperationSchema $head = null;

    /**
     * A definition of a PATCH operation on this path.
     */
    public ?OperationSchema $patch = null;

    /**
     * A list of parameters that are applicable for all the operations described under this path. These parameters can be overridden at the operation level, but cannot be removed there. The list MUST NOT include duplicated parameters. A unique parameter is defined by a combination of a name and location. The list can use the Reference Object to link to parameters that are defined at the Swagger Object's parameters. There can be one "body" parameter at most.
     */
    public ParameterCollection $parameters;

    public function __construct()
    {
        $this->parameters = new ParameterCollection();
    }

    public function exportToArray(): array
    {
        $data = [];

        if ($this->get) {
            $data['get'] = $this->get->toArray();
        }

        if ($this->put) {
            $data['put'] = $this->put->toArray();
        }

        if ($this->post) {
            $data['post'] = $this->post->toArray();
        }

        if ($this->delete) {
            $data['delete'] = $this->delete->toArray();
        }

        if ($this->options) {
            $data['options'] = $this->options->toArray();
        }

        if ($this->head) {
            $data['head'] = $this->head->toArray();
        }

        if ($this->patch) {
            $data['patch'] = $this->patch->toArray();
        }

        // todo: parameters?

        return $data;
    }
}

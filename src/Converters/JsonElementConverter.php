<?php


namespace CodexSoft\Transmission\Schema\Converters;


use CodexSoft\Transmission\Schema\Elements\JsonElement;
use CodexSoft\Transmission\Schema\Elements\NumberElement;

class JsonElementConverter extends AbstractElementConverter
{
    public function __construct(
        protected JsonElement $element,
        protected OpenApiConvertFactory $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        $requiredKeys = [];
        foreach ($this->element->schema as $key => $item) {
            if ($item->isRequired) {
                $requiredKeys[] = $key;
            }
        }
        $data['required'] = $requiredKeys;

        if ($this->element->schemaGatheredFromClass) {
            $data['$ref'] = $this->createRef($this->element->schemaGatheredFromClass);
        } else {
            $properties = [];
            foreach ($this->element->schema as $key => $item) {
                /**
                 * to avoid infinite loops, $refs should be generated in some cases!
                 */
                if ($this->element->schemaGatheredFromClass) {
                    $properties[$key] = [
                        '$ref' => $this->factory->createRef($this->element->schemaGatheredFromClass),
                    ];
                } else {
                    //$properties[$key] = $item->toOpenApiSchema();
                    $properties[$key] = $this->factory->convert($item);
                }
            }
            $data['properties'] = $properties;
        }

        if ($this->element->extraElementSchema) {
            //$data['additionalProperties'] = $this->element->extraElementSchema->toOpenApiSchema();
            $data['additionalProperties'] = $this->factory->convert($this->element->extraElementSchema);
        }

        return $data;
    }
}

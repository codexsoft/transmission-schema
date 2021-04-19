<?php


namespace CodexSoft\Transmission\Schema\Converters;


class JsonElementConverter extends AbstractElementConverter
{

    /**
     * @param \CodexSoft\Transmission\Schema\Elements\JsonElement $element
     *
     * @return array
     */
    public function convert($element): array
    {
        $data = parent::convert($element);

        $requiredKeys = [];
        foreach ($element->schema as $key => $item) {
            if ($item->isRequired) {
                $requiredKeys[] = $key;
            }
        }
        $data['required'] = $requiredKeys;

        if ($element->schemaGatheredFromClass) {
            $data['$ref'] = $this->createRef($element->schemaGatheredFromClass);
        } else {
            $properties = [];
            foreach ($element->schema as $key => $item) {
                /**
                 * to avoid infinite loops, $refs should be generated in some cases!
                 */
                if ($element->schemaGatheredFromClass) {
                    $properties[$key] = [
                        '$ref' => $this->createRef($element->schemaGatheredFromClass),
                    ];
                } else {
                    $properties[$key] = $item->toOpenApiSchema();
                }
            }
            $data['properties'] = $properties;
        }

        if ($element->extraElementSchema) {
            $data['additionalProperties'] = $element->extraElementSchema->toOpenApiSchema();
        }

        return $data;
    }
}

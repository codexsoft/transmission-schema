<?php


namespace CodexSoft\Transmission\Schema\Converters;


use CodexSoft\Transmission\Schema\Elements\CollectionElement;

class CollectionElementConverter extends AbstractElementConverter
{
    /**
     * @param CollectionElement $element
     *
     * @return array
     */
    public function convert($element): array
    {
        $data = parent::convert($element);

        $data['uniqueItems'] = $element->elementsMustBeUnique;

        if ($element->minCount !== null) {
            $data['minItems'] = $element->minCount;
        }

        if ($element->maxCount !== null) {
            $data['maxItems'] = $element->maxCount;
        }

        if ($element->elementSchema !== null) {
            if ($element->schemaGatheredFromClass) {
                $data['items'] = [
                    '$ref' => $this->createRef($element->schemaGatheredFromClass),
                ];
            } else {
                $data['items'] = $element->elementSchema->toOpenApiSchema();
            }

            // 'allOf'
        }

        return $data;
    }
}

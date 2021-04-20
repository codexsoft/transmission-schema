<?php


namespace CodexSoft\Transmission\Schema\Converters;


use CodexSoft\Transmission\Schema\Elements\CollectionElement;

class CollectionElementConverter extends AbstractElementConverter
{
    public function __construct(
        protected CollectionElement $element,
        protected OpenApiConvertFactory $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        $data['uniqueItems'] = $this->element->elementsMustBeUnique;

        if ($this->element->minCount !== null) {
            $data['minItems'] = $this->element->minCount;
        }

        if ($this->element->maxCount !== null) {
            $data['maxItems'] = $this->element->maxCount;
        }

        if ($this->element->elementSchema !== null) {
            if ($this->element->schemaGatheredFromClass) {
                $data['items'] = [
                    '$ref' => $this->factory->createRef($this->element->schemaGatheredFromClass),
                ];
            } else {
                //$data['items'] = $this->element->elementSchema->toOpenApiSchema();
                $data['items'] = $this->factory->convert($this->element->elementSchema);
            }

            // 'allOf'
        }

        return $data;
    }
}

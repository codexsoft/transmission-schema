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

        $data['uniqueItems'] = $this->element->isElementsMustBeUnique();

        if ($this->element->getMinCount() !== null) {
            $data['minItems'] = $this->element->getMinCount();
        }

        if ($this->element->getMaxCount() !== null) {
            $data['maxItems'] = $this->element->getMaxCount();
        }

        if ($this->element->getElementSchema() !== null) {
            if ($this->element->getSchemaSourceClass()) {
                $data['items'] = [
                    '$ref' => $this->factory->createRef($this->element->getSchemaSourceClass()),
                ];
            } else {
                //$data['items'] = $this->element->elementSchema->toOpenApiSchema();
                $data['items'] = $this->factory->convert($this->element->getElementSchema());
            }

            // 'allOf'
        }

        return $data;
    }
}

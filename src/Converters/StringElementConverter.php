<?php


namespace CodexSoft\Transmission\Schema\Converters;


use CodexSoft\Transmission\Schema\Elements\StringElement;

class StringElementConverter extends ScalarElementConverter
{
    public function __construct(
        protected StringElement $element,
        protected OpenApiConvertFactory $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        $data['allowEmptyValue'] = !$this->element->isNotBlank;

        if ($this->element->getPattern() !== null) {
            $data['pattern'] = $this->element->getPattern();
        }

        if ($this->element->minLength !== null) {
            $data['minLength'] = $this->element->minLength;
        }

        if ($this->element->maxLength !== null) {
            $data['maxLength'] = $this->element->maxLength;
        }

        return $data;
    }
}

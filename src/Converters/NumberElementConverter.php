<?php


namespace CodexSoft\Transmission\Schema\Converters;


use CodexSoft\Transmission\Schema\Elements\NumberElement;

class NumberElementConverter extends ScalarElementConverter
{
    public function __construct(
        protected NumberElement $element,
        protected OpenApiConvertFactory $factory
    )
    {
        parent::__construct($element, $factory);
    }

    public function convert(): array
    {
        $data = parent::convert();

        if ($this->element->getMaxValue() !== null) {
            $data['maximum'] = $this->element->getMaxValue();
            $data['exclusiveMaximum'] = $this->element->isExclusiveMaximum();
        }

        if ($this->element->getMinValue() !== null) {
            $data['minimum'] = $this->element->getMinValue();
            $data['exclusiveMinimum'] = $this->element->isExclusiveMinimum();
        }

        return $data;
    }
}

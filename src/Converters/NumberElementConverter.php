<?php


namespace CodexSoft\Transmission\Schema\Converters;


use CodexSoft\Transmission\Schema\Elements\NumberElement;

class NumberElementConverter extends ScalarElementConverter
{

    /**
     * @param NumberElement $element
     *
     * @return array
     */
    public function convert($element): array
    {
        $data = parent::convert($element);

        if ($element->getMaxValue() !== null) {
            $data['maximum'] = $element->getMaxValue();
            $data['exclusiveMaximum'] = $element->isExclusiveMaximum();
        }

        if ($element->getMinValue() !== null) {
            $data['minimum'] = $element->getMinValue();
            $data['exclusiveMinimum'] = $element->isExclusiveMinimum();
        }

        return $data;
    }
}

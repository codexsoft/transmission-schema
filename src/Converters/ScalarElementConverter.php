<?php


namespace CodexSoft\Transmission\Schema\Converters;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;
use CodexSoft\Transmission\Schema\Elements\ScalarElement;

class ScalarElementConverter extends AbstractElementConverter
{
    /**
     * @param ScalarElement $element
     *
     * @return array
     */
    public function convert($element): array
    {
        $data = parent::convert($element);

        if ($element->getPattern()) {
            $data['pattern'] = $element->getPattern();
        }

        if ($element->getChoicesSourceArray()) {
            $data['enum'] = $element->getChoicesSourceArray();

            if ($element->getExample() === AbstractElement::UNDEFINED || !\in_array($element->getExample(), $element->getChoicesSourceArray(), true)) {
                $data['example'] = \array_values($element->getChoicesSourceArray())[0];
            }
        }

        return $data;
    }
}

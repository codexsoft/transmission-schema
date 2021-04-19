<?php


namespace CodexSoft\Transmission\Schema\Converters;


use CodexSoft\Transmission\Schema\Elements\StringElement;

class StringElementConverter extends ScalarElementConverter
{
    /**
     * @param StringElement $element
     *
     * @return array
     */
    public function convert($element): array
    {
        $data = parent::convert($element);

        $data['allowEmptyValue'] = !$element->isNotBlank;

        if ($element->getPattern() !== null) {
            $data['pattern'] = $element->getPattern();
        }

        if ($element->minLength !== null) {
            $data['minLength'] = $element->minLength;
        }

        if ($element->maxLength !== null) {
            $data['maxLength'] = $element->maxLength;
        }

        return $data;
    }
}

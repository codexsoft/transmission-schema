<?php


namespace CodexSoft\Transmission\Schema\Converters;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;
use CodexSoft\Transmission\Schema\Elements\BoolElement;
use CodexSoft\Transmission\Schema\Elements\CollectionElement;
use CodexSoft\Transmission\Schema\Elements\IntegerElement;
use CodexSoft\Transmission\Schema\Elements\JsonElement;
use CodexSoft\Transmission\Schema\Elements\NumberElement;
use CodexSoft\Transmission\Schema\Elements\StringElement;

class AbstractElementConverter
{
    //protected string $openApiType = 'mixed';

    protected function openApiTypeFromElementClass(string $elementClass): string
    {
        /**
         * todo: how to respect inherited openApiType?..
         */
        return match ($elementClass) {
            BoolElement::class => 'boolean',
            CollectionElement::class => 'array',
            IntegerElement::class => 'integer',
            JsonElement::class => 'object',
            NumberElement::class => 'number',
            StringElement::class => 'string',
            default => 'mixed',
        };
    }

    /**
     * @param AbstractElement $element
     *
     * @return array
     */
    public function convert(AbstractElement $element): array
    {
        $data = [
            //'description' => $element->getLabel(),
            'description' => $element->label,
            'type' => $this->openApiTypeFromElementClass(\get_class($element)),
            //'type' => $this->openApiType,
            //'required' => $element->isRequired(),
            'required' => $element->isRequired,
            //'nullable' => $element->isNullable(),
            'nullable' => $element->isNullable,
            //'deprecated' => $element->isDeprecated(),
            'deprecated' => $element->isDeprecated,
        ];

        //if ($element->getExample() !== AbstractElement::UNDEFINED) {
        if ($element->example !== AbstractElement::UNDEFINED) {
            //$data['example'] = $element->getExample();
            $data['example'] = $element->example;
        }

        if ($element->hasDefaultValue()) {
            //$data['default'] = $element->getDefaultValue();
            $data['default'] = $element->defaultValue;
        }

        return $data;
    }
}

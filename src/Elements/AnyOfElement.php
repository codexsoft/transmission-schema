<?php


namespace CodexSoft\Transmission\Schema\Elements;


class AnyOfElement extends AbstractCompositeElement
{
    /** @var AbstractElement[] */
    protected array $variants;

    /**
     * AnyOfElement constructor.
     *
     * @param AbstractElement[] $variants
     * @param string $label
     */
    public function __construct(array $variants, string $label = '')
    {
        parent::__construct($label);
        $this->variants = $variants;
    }

    public function toOpenApiSchema(): array
    {
        $oaVariants = [];
        foreach ($this->variants as $variant) {
            $oaVariants[] = $variant->toOpenApiSchema();
        }

        $data = [
            'anyOf' => $oaVariants,
            'description' => $this->label,
            'required' => $this->isRequired,
            'nullable' => $this->isNullable,
            'deprecated' => $this->isDeprecated,
        ];

        //if ($this->example !== self::UNDEFINED) {
        //    $data['example'] = $this->example;
        //}

        //if ($this->hasDefaultValue()) {
        //    $data['default'] = $this->defaultValue;
        //}

        return $data;
    }
}

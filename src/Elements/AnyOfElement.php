<?php


namespace CodexSoft\Transmission\Schema\Elements;

/**
 * Warning! Currently data normalization/validation is not possible for this element.
 * Use for documenting output schemas.
 *
 * Also, seems that additionalProperties does not support anyOf key (redoc-cli failed to handle this)
 */
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

        return $data;
    }
}

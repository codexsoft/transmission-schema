<?php


namespace CodexSoft\Transmission\Schema\Elements;

/**
 * Warning! Currently data normalization/validation is not possible for this element.
 * Use for documenting output schemas.
 *
 * Also, seems that additionalProperties does not support anyOf key (redoc-cli failed to handle this)
 *
 * Also, seems that generally `anyOf` key cause failure in redoc-cli
 */
class AnyOfElement extends AbstractCompositeElement
{
    /** @var BasicElement[] */
    protected array $variants;

    /**
     * AnyOfElement constructor.
     *
     * @param BasicElement[] $variants
     * @param string $label
     */
    public function __construct(array $variants, string $label = '')
    {
        parent::__construct($label);
        $this->variants = $variants;
    }

    /**
     * @return BasicElement[]
     */
    public function getVariants(): array
    {
        return $this->variants;
    }
}

<?php


namespace CodexSoft\Transmission\Schema\Elements;


class AnyOfElement extends AbstractCompositeElement
{
    protected array $variants;

    public function __construct(array $variants, string $label = '')
    {
        parent::__construct($label);
        $this->variants = $variants;
    }

    public function toOpenApiSchema(): array
    {
        // TODO: Implement toOpenApiSchema() method.
    }
}

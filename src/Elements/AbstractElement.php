<?php


namespace CodexSoft\Transmission\Schema\Elements;


abstract class AbstractElement implements OpenApiAwareInterface, AbstractElementBuilderInterface
{
    use AbstractElementBuilderTrait;

    protected string $label = '';
    protected bool $isDeprecated = false;
    protected bool $isRequired = true;
    protected bool $isNullable = false;

    public function __construct(string $label = '')
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->isDeprecated;
    }

    /**
     * @deprecated
     * @return array
     */
    abstract public function toOpenApiSchema(): array;
}

<?php


namespace CodexSoft\Transmission\Schema\Elements;


abstract class AbstractBaseElement implements OpenApiAwareInterface, AbstractBaseElementBuilderInterface
{
    use AbstractBaseElementBuilderTrait;

    /** @internal */
    public string $label = '';
    /** @internal */
    public bool $isDeprecated = false;
    /** @internal */
    public bool $isRequired = true;
    /** @internal */
    public bool $isNullable = false;

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

    public function __construct(string $label = '')
    {
        $this->label = $label;
    }

    /**
     * @deprecated
     * @return array
     */
    abstract public function toOpenApiSchema(): array;
}

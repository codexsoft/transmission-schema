<?php


namespace CodexSoft\Transmission\Schema\Elements;


abstract class AbstractBaseElement
{
    protected string $label = '';
    protected bool $isDeprecated = false;
    protected bool $isRequired = true;
    protected bool $isNullable = false;

    public function __construct(string $label = '')
    {
        $this->label = $label;
    }

    abstract public function toOpenApiSchema(): array;

    /**
     * Set short text label for element
     * @param string $label
     *
     * @return static
     */
    public function label(string $label)
    {
        $this->label = $label;
        return $this;
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
     * Set that element value CANNOT be null
     * @return static
     */
    public function notNull()
    {
        $this->isNullable = false;
        return $this;
    }

    /**
     * Set that element value CAN be null
     * @return static
     */
    public function nullable()
    {
        $this->isNullable = true;
        return $this;
    }

    /**
     * @param bool $isDeprecated
     *
     * @return static
     */
    public function deprecated(bool $isDeprecated = true): self
    {
        $this->isDeprecated = $isDeprecated;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->isDeprecated;
    }
}

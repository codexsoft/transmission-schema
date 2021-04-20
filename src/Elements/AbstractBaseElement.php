<?php


namespace CodexSoft\Transmission\Schema\Elements;


abstract class AbstractBaseElement implements OpenApiAwareInterface, AbstractBaseElementBuilderInterface
{
    //use AbstractBaseElementFieldsTrait;
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

    ///** @internal */
    //public string $label = '';
    ///** @internal */
    //public bool $isDeprecated = false;
    ///** @internal */
    //public bool $isRequired = true;
    ///** @internal */
    //public bool $isNullable = false;

    public function __construct(string $label = '')
    {
        $this->label = $label;
    }

    /**
     * @deprecated
     * @return array
     */
    abstract public function toOpenApiSchema(): array;

    ///**
    // * @param bool $isDeprecated
    // *
    // * @return static
    // */
    //public function setIsDeprecated(bool $isDeprecated): self
    //{
    //    $this->isDeprecated = $isDeprecated;
    //    return $this;
    //}
    //
    ///**
    // * @param bool $isRequired
    // *
    // * @return static
    // */
    //public function setIsRequired(bool $isRequired): self
    //{
    //    $this->isRequired = $isRequired;
    //    return $this;
    //}
    //
    ///**
    // * @param bool $isNullable
    // *
    // * @return static
    // */
    //public function setIsNullable(bool $isNullable): self
    //{
    //    $this->isNullable = $isNullable;
    //    return $this;
    //}

    ///**
    // * @param string $label
    // *
    // * @return AbstractBaseElement
    // */
    //public function setLabel(string $label): AbstractBaseElement
    //{
    //    $this->label = $label;
    //    return $this;
    //}

    ///**
    // * Set short text label for element
    // * @param string $label
    // *
    // * @return static
    // */
    //public function label(string $label)
    //{
    //    $this->label = $label;
    //    return $this;
    //}

    ///**
    // * @return string
    // */
    //public function getLabel(): string
    //{
    //    return $this->label;
    //}

    ///**
    // * @return bool
    // */
    //public function isRequired(): bool
    //{
    //    return $this->isRequired;
    //}

    ///**
    // * @return bool
    // */
    //public function isNullable(): bool
    //{
    //    return $this->isNullable;
    //}

    ///**
    // * Set that element value CANNOT be null
    // * @return static
    // */
    //public function notNull()
    //{
    //    $this->isNullable = false;
    //    return $this;
    //}

    ///**
    // * Set that element value CAN be null
    // * @return static
    // */
    //public function nullable()
    //{
    //    $this->isNullable = true;
    //    return $this;
    //}

    ///**
    // * @param bool $isDeprecated
    // *
    // * @return static
    // */
    //public function deprecated(bool $isDeprecated = true): self
    //{
    //    $this->isDeprecated = $isDeprecated;
    //    return $this;
    //}

    ///**
    // * @return bool
    // */
    //public function isDeprecated(): bool
    //{
    //    return $this->isDeprecated;
    //}
}

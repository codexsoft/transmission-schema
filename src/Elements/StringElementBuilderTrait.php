<?php


namespace CodexSoft\Transmission\Schema\Elements;


trait StringElementBuilderTrait
{
    /**
     * @param null $min
     * @param null $max
     *
     * @return static
     */
    public function length($min = null, $max = null): static
    {
        $this->minLength = $min;
        $this->maxLength = $max;
        return $this;
    }

    /**
     * @param int $min
     *
     * @return static
     */
    public function minLength(int $min): static
    {
        $this->minLength = $min;
        return $this;
    }

    /**
     * @param int $max
     *
     * @return static
     */
    public function maxLength(int $max): static
    {
        $this->maxLength = $max;
        return $this;
    }

    /**
     * @return static
     */
    public function noWhiteSpace(): static
    {
        $this->noWhiteSpace = true;
        return $this;
    }

    /**
     * @param bool $stripTags
     *
     * @return static
     */
    public function stripTags(bool $stripTags = true): static
    {
        $this->stripTags = $stripTags;
        return $this;
    }

    /**
     * @param bool $trim
     *
     * @return static
     */
    public function trim(bool $trim = true): static
    {
        $this->trim = $trim;
        return $this;
    }

    /**
     * @param bool $isAlphaNumeric
     *
     * @return static
     */
    public function alnum(bool $isAlphaNumeric = true): static
    {
        $this->isAlphaNumeric = $isAlphaNumeric;
        return $this;
    }

    /**
     * @return static
     */
    public function notBlank(): static
    {
        $this->isNotBlank = true;
        return $this;
    }

    /**
     * SHOULD be a valid regular expression
     * @param string|null $pattern
     *
     * @return static
     */
    public function pattern(?string $pattern): static
    {
        $this->pattern = $pattern;
        return $this;
    }
}

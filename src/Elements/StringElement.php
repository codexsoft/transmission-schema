<?php


namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\Constraints\ContainsAlphanumeric;
use CodexSoft\Transmission\Schema\Constraints\ContainsNoWhitespaces;
use Symfony\Component\Validator\Constraints;

class StringElement extends ScalarElement
{
    protected ?array $acceptedPhpTypes = ['string'];
    protected string $openApiType = 'string';
    protected $example = 'Some text sample';

    /**
     * Example: /^[a-z]+$/
     * @var string|null
     */
    protected ?string $pattern = null;

    /*
     * Processing
     */

    protected bool $stripTags = false;
    protected bool $trim = false;

    /*
     * Constraints
     */

    protected ?int $minLength = null;
    protected ?int $maxLength = null;
    protected bool $noWhiteSpace = false;
    protected bool $isNotBlank = false;
    protected bool $isAlphaNumeric = false;

    /**
     * @deprecated
     * @return array
     */
    public function toOpenApiSchema(): array
    {
        $data = parent::toOpenApiSchema();
        $data['allowEmptyValue'] = !$this->isNotBlank;

        if ($this->pattern !== null) {
            $data['pattern'] = $this->pattern;
        }

        if ($this->minLength !== null) {
            $data['minLength'] = $this->minLength;
        }

        if ($this->maxLength !== null) {
            $data['maxLength'] = $this->maxLength;
        }

        return $data;
    }

    /**
     * @return static
     */
    public function notBlank()
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
    public function pattern(?string $pattern): self
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @param $data
     *
     * @return string
     * @inheritDoc
     */
    protected function doNormalizeData($data): ?string
    {
        $data = parent::doNormalizeData($data);
        $data = (string) $data;

        if ($this->stripTags) {
            $data = \strip_tags($data);
        }

        if ($this->trim) {
            $data = \trim($data);
        }

        return $data;
    }

    /**
     * @param bool $stripTags
     *
     * @return static
     */
    public function stripTags(bool $stripTags = true): self
    {
        $this->stripTags = $stripTags;
        return $this;
    }

    /**
     * @param bool $trim
     *
     * @return static
     */
    public function trim(bool $trim = true): self
    {
        $this->trim = $trim;
        return $this;
    }

    /**
     * @param bool $isAlphaNumeric
     *
     * @return static
     */
    public function alnum(bool $isAlphaNumeric = true): self
    {
        $this->isAlphaNumeric = $isAlphaNumeric;
        return $this;
    }

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();

        $lengthOptions = [];
        if ($this->minLength !== null) {
            $constraints[] = new Constraints\Length(['min' => $this->minLength]);
        }
        if ($this->maxLength !== null) {
            $constraints[] = new Constraints\Length(['max' => $this->maxLength]);
        }

        if ($this->pattern) {
            $constraints[] = new Constraints\Regex($this->pattern);
        }

        if ($this->isNotBlank) {
            $constraints[] = new Constraints\NotBlank([
                'allowNull' => $this->isNullable,
            ]);
        }

        if ($this->noWhiteSpace) {
            $constraints[] = new ContainsNoWhitespaces();
        }

        if ($this->isAlphaNumeric) {
            $constraints[] = new ContainsAlphanumeric();
        }

        if ($this->pattern !== null) {
            $constraints[] = new Constraints\Regex(['pattern' => $this->pattern]);
        }

        return $constraints;
    }

    /**
     * @param null $min
     * @param null $max
     *
     * @return static
     */
    public function length($min = null, $max = null): self
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
    public function minLength(int $min): self
    {
        $this->minLength = $min;
        return $this;
    }

    /**
     * @param int $max
     *
     * @return static
     */
    public function maxLength(int $max): self
    {
        $this->maxLength = $max;
        return $this;
    }

    /**
     * @return static
     */
    public function noWhiteSpace(): self
    {
        $this->noWhiteSpace = true;
        return $this;
    }
}

<?php


namespace CodexSoft\Transmission\Elements;

use CodexSoft\Transmission\Constraints\ContainsAlphanumeric;
use CodexSoft\Transmission\Constraints\ContainsNoWhitespaces;
use Symfony\Component\Validator\Constraints;

class StringElement extends ScalarElement
{

    protected ?array $acceptedPhpTypes = ['string'];
    protected string $openApiType = 'string';
    protected $example = 'Some text sample';

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

    public function toOpenApiV2ParameterArray(): array
    {
        $data = parent::toOpenApiV2ParameterArray();
        $data['allowEmptyValue'] = !$this->isNotBlank;

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
     * @param $data
     *
     * @return string
     * @inheritDoc
     */
    public function doNormalizeData($data): ?string
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
    public function stripTags(bool $stripTags = true)
    {
        $this->stripTags = $stripTags;
        return $this;
    }

    /**
     * @param bool $trim
     *
     * @return static
     */
    public function trim(bool $trim = true)
    {
        $this->trim = $trim;
        return $this;
    }

    /**
     * @param bool $isAlphaNumeric
     *
     * @return static
     */
    public function alnum(bool $isAlphaNumeric = true)
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
            //$lengthOptions['min'] = $this->minLength;
        }
        if ($this->maxLength !== null) {
            $constraints[] = new Constraints\Length(['max' => $this->maxLength]);
            //$lengthOptions['max'] = $this->maxLength;
        }
        //if ($lengthOptions) {
        //    $constraints[] = new Constraints\Length($lengthOptions);
        //}

        if ($this->isNotBlank) {
            $constraints[] = new Constraints\NotBlank();
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
    public function length($min = null, $max = null)
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
    public function minLength(int $min)
    {
        $this->minLength = $min;
        return $this;
    }

    /**
     * @param int $max
     *
     * @return static
     */
    public function maxLength(int $max)
    {
        $this->maxLength = $max;
        return $this;
    }

    /**
     * @return static
     */
    public function noWhiteSpace()
    {
        $this->noWhiteSpace = true;
        return $this;
    }
}

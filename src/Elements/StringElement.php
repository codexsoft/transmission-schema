<?php


namespace CodexSoft\Transmission\Elements;

use Symfony\Component\Validator\Constraints;

class StringElement extends ScalarElement
{

    protected ?array $acceptedTypes = ['string'];
    protected $example = 'Some text sample';

    protected bool $isNotBlank = false;

    /*
     * Processing
     */

    protected bool $stripTags = false;
    protected bool $trim = true;

    /*
     * Constraints
     */

    protected ?int $minLength = null;
    protected ?int $maxLength = null;
    protected bool $noWhiteSpace = false;

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

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();

        $lengthOptions = [];
        if ($this->minLength !== null) {
            $lengthOptions['min'] = $this->minLength;
        }
        if ($this->maxLength !== null) {
            $lengthOptions['max'] = $this->maxLength;
        }
        if ($lengthOptions) {
            $constraints[] = new Constraints\Length($lengthOptions);
        }

        if ($this->isNotBlank) {
            $constraints[] = new Constraints\NotBlank();
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

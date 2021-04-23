<?php


namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\Constraints\ContainsAlphanumeric;
use CodexSoft\Transmission\Schema\Constraints\ContainsNoWhitespaces;
use Symfony\Component\Validator\Constraints;

class StringElement extends ScalarElement implements StringElementBuilderInterface
{
    use StringElementBuilderTrait;

    protected ?array $acceptedPhpTypes = ['string'];
    protected mixed $example = 'Some text sample';

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
     * @return int|null
     */
    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    /**
     * @return int|null
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    /**
     * @return bool
     */
    public function isNoWhiteSpace(): bool
    {
        return $this->noWhiteSpace;
    }

    /**
     * @return bool
     */
    public function isNotBlank(): bool
    {
        return $this->isNotBlank;
    }

    /**
     * @return bool
     */
    public function isAlphaNumeric(): bool
    {
        return $this->isAlphaNumeric;
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    protected function doNormalizeData(mixed $data): ?string
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
     * @return string|null
     */
    public function getPattern(): ?string
    {
        return $this->pattern;
    }
}

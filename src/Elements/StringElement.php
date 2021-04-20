<?php


namespace CodexSoft\Transmission\Schema\Elements;

use CodexSoft\Transmission\Schema\Constraints\ContainsAlphanumeric;
use CodexSoft\Transmission\Schema\Constraints\ContainsNoWhitespaces;
use Symfony\Component\Validator\Constraints;

class StringElement extends ScalarElement implements StringElementBuilderInterface
{
    use StringElementBuilderTrait;

    protected ?array $acceptedPhpTypes = ['string'];
    protected string $openApiType = 'string';
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


}

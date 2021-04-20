<?php


namespace CodexSoft\Transmission\Schema\Elements;

use Symfony\Component\Validator\Constraints;

/**
 * Represents JSON array
 */
class CollectionElement extends AbstractElement implements CompositeElementInterface, ReferencableElementInterface, CollectionElementBuilderInterface
{
    use CollectionElementBuilderTrait;

    protected ?array $acceptedPhpTypes = ['array'];
    protected string $openApiType = 'array';

    private ?AbstractElement $elementSchema = null;
    protected ?string $schemaGatheredFromClass = null;
    protected bool $strictTypeCheck = true;

    private ?int $minCount = null;
    private ?int $maxCount = null;
    private bool $elementsMustBeUnique = false;

    /**
     * @deprecated
     * @return array
     * @throws \ReflectionException
     */
    public function toOpenApiSchema(): array
    {
        $data = parent::toOpenApiSchema();
        $data['uniqueItems'] = $this->elementsMustBeUnique;

        if ($this->minCount !== null) {
            $data['minItems'] = $this->minCount;
        }

        if ($this->maxCount !== null) {
            $data['maxItems'] = $this->maxCount;
        }

        if ($this->elementSchema !== null) {
            if ($this->schemaGatheredFromClass) {
                $data['items'] = [
                    '$ref' => $this->createRef($this->schemaGatheredFromClass),
                ];
            } else {
                $data['items'] = $this->elementSchema->toOpenApiSchema();
            }

            // 'allOf'
        }

        return $data;
    }

    /**
     * @return string[]
     */
    public function collectMentionedSchemas(): array
    {
        $mentioned = [];

        if ($this->schemaGatheredFromClass) {
            return [$this->schemaGatheredFromClass];
        }

        if ($this->elementSchema instanceof CompositeElementInterface) {
            \array_push($mentioned, ...$this->elementSchema->collectMentionedSchemas());
        }

        return $mentioned;
    }



    /**
     * @return AbstractElement|null
     */
    public function getElementSchema(): ?AbstractElement
    {
        return $this->elementSchema;
    }

    /**
     * @param $data
     *
     * @return array|mixed
     */
    protected function doNormalizeData($data)
    {
        $data = parent::doNormalizeData($data);
        $normalizedData = [];
        if ($this->elementSchema) {
            foreach ($data as $datum) {
                $normalizedData[] = $this->elementSchema->normalizeData($datum);
            }
            return $normalizedData;
        }

        return $data;
    }

    protected function generateSfConstraints(): array
    {
        $constraints = parent::generateSfConstraints();

        $countRestrictions = [];
        if ($this->minCount) {
            $countRestrictions['min'] = $this->minCount;
        }
        if ($this->maxCount) {
            $countRestrictions['max'] = $this->maxCount;
        }
        if ($countRestrictions) {
            $constraints[] = new Constraints\Count($countRestrictions);
        }

        if ($this->elementsMustBeUnique) {
            $constraints[] = new Constraints\Unique();
        }

        if ($this->elementSchema) {
            $compiledElementSchema = $this->elementSchema->compileToSymfonyValidatorConstraint();
            $constraints[] = new Constraints\All(['constraints' => $compiledElementSchema]);
        }

        return $constraints;
    }

    protected function generateFormalSfConstraints(): array
    {
        $constraints = parent::generateFormalSfConstraints();

        if ($this->elementSchema) {
            $compiledElementSchema = $this->elementSchema->compileToFormalSymfonyValidatorConstraint();
            $constraints[] = new Constraints\All(['constraints' => $compiledElementSchema]);
        }

        //$constraints[] = new Constraints\Type(['type' => 'array']);

        return $constraints;
    }

    ///**
    // * @return Constraint|Constraint[]
    // */
    //public function compileToSymfonyValidatorConstraint()
    //{
    //    $constraints = \array_merge($this->generateSfConstraints(), $this->customSfConstraints);
    //
    //    return $this->isRequired ? $constraints : new Constraints\Optional($constraints);
    //}

    public function isReference(): bool
    {
        return $this->schemaGatheredFromClass !== null;
    }

    public function getReferencedClass(): ?string
    {
        return $this->schemaGatheredFromClass;
    }
}

<?php


namespace CodexSoft\Transmission\Schema\Typescript;


use CodexSoft\Transmission\Schema\Elements\AbstractBaseElement;
use CodexSoft\Transmission\Schema\Elements\AnyOfElement;
use CodexSoft\Transmission\Schema\Elements\BoolElement;
use CodexSoft\Transmission\Schema\Elements\CollectionElement;
use CodexSoft\Transmission\Schema\Elements\JsonElement;
use CodexSoft\Transmission\Schema\Elements\NumberElement;
use CodexSoft\Transmission\Schema\Elements\ReferencableElementInterface;
use CodexSoft\Transmission\Schema\Elements\ScalarElement;
use CodexSoft\Transmission\Schema\Elements\StringElement;

class TransmissionObjectToTypescriptInterfaceConverter
{
    protected ?\Closure $fromRefToInterface = null;
    protected ?\Closure $onFoundReference = null;

    public function __construct(private bool $useReferences = true)
    {
    }

    protected function convertRefToInterface(string $refClass): string
    {
        if ($this->fromRefToInterface) {
            return ($this->fromRefToInterface)($refClass);
        }

        return 'I'.\str_replace("\\", '_', $refClass);
    }

    public function stringifyTypescriptObject(array $data, $indent = '    '): string
    {
        $result = "{\n";
        $lines = [];
        foreach ($data as $key => $value) {
            $lines[] = $indent.$key.': '.$value;
        }
        $result .= \implode(",\n", $lines);
        $result .= "\n}";

        return $result;
    }

    public function convertJsonElementToTypescriptObject(JsonElement $element): array
    {
        $result = [];

        foreach ($element->getSchema() as $key => $subSchema) {
            $keyName = $key;
            if (!$subSchema->isRequired()) {
                $keyName = $key.'?';
            }

            $result[$keyName] = $this->convertElementToTypescriptType($subSchema);
        }

        if ($element->extraElementSchema()) {
            /* Object in JSON can have string keys ONLY */
            $result['[k: string]'] = $this->convertElementToTypescriptType($element->getExtraElementSchema());
        }

        return $result;
    }

    private function detectTypescriptType(AbstractBaseElement $element): string
    {
        if ($element instanceof BoolElement) {
            return 'boolean';
        }

        if ($element instanceof StringElement) {
            return 'string';
        }

        if ($element instanceof NumberElement) {
            return  'number';
        }

        if ($element instanceof ScalarElement) {
            return 'number|string|boolean';
        }

        return 'any';
    }

    /**
     * todo: optionate using refs?
     *
     * @param array $element
     *
     * @return string
     */
    private function convertElementToTypescriptType(AbstractBaseElement $element): string
    {
        if ($this->useReferences && $element instanceof ReferencableElementInterface && $element->isReference()) {
            $tsType = $this->convertRefToInterface($element->getReferencedClass());
            if ($this->onFoundReference) {
                ($this->onFoundReference)(new FoundReferencedClassEvent($element->getReferencedClass(), $tsType));
            }
        } else if ($element instanceof AnyOfElement) {
            $tsVariantsArr = [];
            foreach ($element->getVariants() as $variant) {
                $tsVariantsArr[] = $this->convertElementToTypescriptType($variant);
            }
            $tsType = \implode(' | ', $tsVariantsArr);
        } else if ($element instanceof JsonElement) {
            $tsType = $this->stringifyTypescriptObject($this->convertJsonElementToTypescriptObject($element));
        } else if ($element instanceof CollectionElement) {
            $tsType = 'Array<'.$this->convertElementToTypescriptType($element->getElementSchema()).'>';
        } else {
            $tsType = $this->detectTypescriptType($element);

            if ($element instanceof ScalarElement && $element->getChoicesSourceArray()) {
                $exported = \array_map(static function ($val) {
                    return \var_export($val, true);
                }, $element->getChoicesSourceArray());
                $tsType = \implode('|', $exported);
            }
        }

        if ($element->isNullable()) {
            $tsType .= '|null';
        }

        return $tsType;
    }

    /**
     * @param \Closure|null $onFoundReference
     *
     * @return static
     */
    public function setOnFoundReference(?\Closure $onFoundReference): static
    {
        $this->onFoundReference = $onFoundReference;
        return $this;
    }

    /**
     * @param \Closure|null $fromRefToInterface
     *
     * @return static
     */
    public function setFromRefToInterface(?\Closure $fromRefToInterface): static
    {
        $this->fromRefToInterface = $fromRefToInterface;
        return $this;
    }
}

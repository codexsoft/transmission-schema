<?php


namespace CodexSoft\Transmission\Schema\Elements;

/**
 * @deprecated
 */
class BuilderToElementConverter
{
    /**
     * @param array<string, ElementBuilderInterface|AbstractElement> $schema
     *
     * @return AbstractElement
     * @throws \CodexSoft\Transmission\Schema\Exceptions\InvalidJsonSchemaException
     */
    public static function normalizeToJsonElement(array $schema): AbstractElement
    {
        $result = static::normalize($schema);
        if (\is_array($schema)) {
            return new JsonElement($result);
        }

        return $result;
    }

    /**
     * @param mixed $schema
     *
     * @return AbstractElement|AbstractElement[]
     */
    public static function normalize(mixed $schema): AbstractElement|array
    {
        if ($schema instanceof AbstractBaseElement) {
            return $schema;
        }

        if ($schema instanceof ElementBuilderInterface) {
            return $schema->build();
        }

        if (\is_array($schema)) {
            $result = [];
            foreach ($schema as $key => $item) {
                $result[$key] = static::normalize($item);
            }
            return $result;
        }
    }
}

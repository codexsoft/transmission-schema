<?php


namespace CodexSoft\Transmission\Tools;

/**
 * todo: transform method result can be checked formally using schema
 * @method static array transform($entity, ...$context)
 */
abstract class AbstractDataTransformer
{
    /**
     * Just a helper to transform array of entities
     * @param array $entities
     * @param mixed ...$context
     *
     * @return array
     */
    public static function transformCollection(array $entities, ...$context): array
    {
        $result = [];
        foreach ($entities as $entity) {
            $result[] = static::transform($entity, ...$context);
        }
        return $result;
    }
}

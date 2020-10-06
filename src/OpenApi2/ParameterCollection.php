<?php


namespace CodexSoft\Transmission\OpenApi2;


use CodexSoft\Transmission\Elements\AbstractElement;

/**
 * @method ParameterSchema[] toArray()
 * @method ParameterSchema|false current()
 * @method ParameterSchema|false first()
 * @method ParameterSchema|false last()
 * @method ParameterSchema|false next()
 * @method ParameterSchema|false get($key)
 * @method ParameterSchema[] slice()
 * @method true add(ParameterSchema $element)
 * @method void set(string|int $key, ParameterSchema $element)
 */
class ParameterCollection extends AbstractSchemaCollection
{
    public static function importFromElement(AbstractElement $element): void
    {
    }
}

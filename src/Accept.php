<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\AbstractElement;

/**
 * @method static Elements\BoolElement bool(string $label = '')
 * @method static Elements\DateElement date(string $label = '')
 * @method static Elements\EmailElement email(string $label = '')
 * @method static Elements\FloatElement double(string $label = '')
 * @method static Elements\FloatElement float(string $label = '')
 * @method static Elements\IdElement id(string $label = '')
 * @method static Elements\IntegerElement integer(string $label = '')
 * @method static Elements\NumberElement number(string $label = '')
 * @method static Elements\ScalarElement scalar(string $label = '')
 * @method static Elements\StringElement string(string $label = '')
 * @method static Elements\StringElement text(string $label = '')
 * @method static Elements\TimeElement time(string $label = '')
 * @method static Elements\TimestampElement timestamp(string $label = '')
 * @method static Elements\UrlElement url(string $label = '')
 * @method static Elements\UuidElement uuid(string $label = '')
 */
class Accept
{
    protected const MAP = [
        'bool' => Elements\BoolElement::class,
        'date' => Elements\DateElement::class,
        'double' => Elements\FloatElement::class,
        'email' => Elements\EmailElement::class,
        'float' => Elements\FloatElement::class,
        'id' => Elements\IdElement::class,
        'integer' => Elements\IntegerElement::class,
        'number' => Elements\NumberElement::class,
        'scalar' => Elements\ScalarElement::class,
        'string' => Elements\StringElement::class,
        'text' => Elements\StringElement::class,
        'time' => Elements\TimeElement::class,
        'timestamp' => Elements\TimestampElement::class,
        'url' => Elements\UrlElement::class,
        'uuid' => Elements\UuidElement::class,
    ];

    /**
     * @param AbstractElement|string $elementSchema
     * @param string $label
     *
     * @return Elements\CollectionElement
     * @throws Exceptions\InvalidCollectionElementSchemaException
     */
    public static function collection($elementSchema, string $label = ''): Elements\CollectionElement
    {
        return (new Elements\CollectionElement($label))->each($elementSchema);
    }

    /**
     * @param AbstractElement[]|string $schema
     * @param string $label
     *
     * @return Elements\JsonElement
     * @throws Exceptions\InvalidJsonSchemaException
     */
    public static function json($schema, string $label = ''): Elements\JsonElement
    {
        return (new Elements\JsonElement($schema, $label));
    }

    public static function __callStatic($name, $arguments)
    {
        if (\array_key_exists($name, static::MAP)) {
            $class = static::MAP[$name];
            return new $class($arguments[0] ?? '', $arguments[1] ?? []);
        }
        throw new \BadMethodCallException('Method '.$name.' does not exist');
    }
}

<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\AbstractElement;

/**
 * @method static Elements\UrlElement url(string $label = '', array $constraints = [])
 * @method static Elements\StringElement text(string $label = '', array $constraints = [])
 * @method static Elements\StringElement string(string $label = '', array $constraints = [])
 * @method static Elements\EmailElement email(string $label = '', array $constraints = [])
 * @method static Elements\IntegerElement integer(string $label = '', array $constraints = [])
 * @method static Elements\FloatElement float(string $label = '', array $constraints = [])
 * @method static Elements\BoolElement bool(string $label = '', array $constraints = [])
 * @method static Elements\FloatElement double(string $label = '', array $constraints = [])
 * @method static Elements\NumberElement number(string $label = '', array $constraints = [])
 * @method static Elements\TimestampElement timestamp(string $label = '', array $constraints = [])
 * @method static Elements\IdElement id(string $label = '', array $constraints = [])
 * @method static Elements\UuidElement uuid(string $label = '', array $constraints = [])
 * @method static Elements\ScalarElement scalar(string $label = '', array $constraints = [])
 * @method static Elements\DateElement date(string $label = '', array $constraints = [])
 */
class Accept
{
    protected const MAP = [
        'url' => Elements\UrlElement::class,
        'text' => Elements\StringElement::class,
        'string' => Elements\StringElement::class,
        'email' => Elements\EmailElement::class,
        'integer' => Elements\IntegerElement::class,
        'float' => Elements\FloatElement::class,
        'bool' => Elements\BoolElement::class,
        'double' => Elements\FloatElement::class,
        'number' => Elements\NumberElement::class,
        'timestamp' => Elements\TimestampElement::class,
        'id' => Elements\IdElement::class,
        'uuid' => Elements\UuidElement::class,
        'scalar' => Elements\ScalarElement::class,
        'date' => Elements\DateElement::class,
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
     * @param null $schema
     * @param string $label
     *
     * @return Elements\JsonElement
     * @throws Exceptions\InvalidJsonSchemaException
     */
    public static function json($schema = null, string $label = ''): Elements\JsonElement
    {
        return (new Elements\JsonElement($schema, $label));
    }

    //public static function oneOf(string $label = '', ...$parts): Parts\OneOfPart
    //{
    //    return (new Parts\OneOfPart($label));
    //}

    public static function __callStatic($name, $arguments)
    {
        if (\array_key_exists($name, static::MAP)) {
            $class = static::MAP[$name];
            return new $class($arguments[0] ?? '', $arguments[1] ?? []);
        }
        throw new \BadMethodCallException('Method '.$name.' does not exist');
    }
}

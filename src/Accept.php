<?php


namespace CodexSoft\Transmission\Schema;


use CodexSoft\Transmission\Schema\Elements\AbstractElement;

/**
 * Fascade for constructing elements
 * You can extend this class for your needs
 *
 //* @method static Elements\AnyOfElement anyOf(string $label = '')
 * @method static Elements\ScalarElementBuilderInterface bool(string $label = '')
 * @method static Elements\StringElementBuilderInterface date(string $label = '')
 * @method static Elements\StringElementBuilderInterface datetime(string $label = '')
 * @method static Elements\StringElementBuilderInterface email(string $label = '')
 * @method static Elements\NumberElementBuilderInterface double(string $label = '')
 * @method static Elements\NumberElementBuilderInterface float(string $label = '')
 * @method static Elements\NumberElementBuilderInterface id(string $label = '')
 * @method static Elements\NumberElementBuilderInterface integer(string $label = '')
 * @method static Elements\NumberElementBuilderInterface number(string $label = '')
 * @method static Elements\ScalarElementBuilderInterface scalar(string $label = '')
 * @method static Elements\StringElementBuilderInterface string(string $label = '')
 * @method static Elements\StringElementBuilderInterface text(string $label = '')
 * @method static Elements\StringElementBuilderInterface time(string $label = '')
 * @method static Elements\NumberElementBuilderInterface timestamp(string $label = '')
 * @method static Elements\StringElementBuilderInterface url(string $label = '')
 * @method static Elements\StringElementBuilderInterface uuid(string $label = '')
 */
class Accept
{
    protected const MAP = [
        //'anyOf' => Elements\AnyOfElement::class,
        'bool' => Elements\BoolElement::class,
        'date' => Elements\DateElement::class,
        'datetime' => Elements\DateTimeElement::class,
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
     * @param array $variants
     * @param string $label
     *
     * @return Elements\AnyOfElement
     */
    public static function anyOf(array $variants, string $label = ''): Elements\AnyOfElement
    {
        return (new Elements\AnyOfElement($variants, $label));
    }

    /**
     * @param AbstractElement|string $elementSchema
     * @param string $label
     *
     * @return Elements\CollectionElementBuilderInterface
     * @throws Exceptions\InvalidCollectionElementSchemaException
     */
    public static function collection($elementSchema, string $label = ''): Elements\CollectionElementBuilderInterface
    {
        return (new Elements\CollectionElement($label))->each($elementSchema);
    }

    /**
     * @param AbstractElement[]|string $schema schema of known elements (dynamical keys signature can be added by separate method)
     * @param string $label
     *
     * @return Elements\JsonElementBuilderInterface
     * @throws Exceptions\InvalidJsonSchemaException
     */
    public static function json($schema, string $label = ''): Elements\JsonElementBuilderInterface
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

<?php


namespace CodexSoft\Transmission\OpenApi2;

/**
 * A Swagger "XML Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#xmlObject
 */
class XmlSchema
{
    /**
     * Replaces the name of the element/attribute used for the described schema property. When
     * defined within the Items Object (items), it will affect the name of the individual XML
     * elements within the list. When defined alongside type being array (outside the items), it
     * will affect the wrapping element and only if wrapped is true. If wrapped is false, it will
     * be ignored.
     * @var string
     */
    public $name;

    /**
     * The URL of the namespace definition. Value SHOULD be in the form of a URL.
     * @var string
     */
    public $namespace;

    /**
     * The prefix to be used for the name.
     * @var string
     */
    public $prefix;

    /**
     * Declares whether the property definition translates to an attribute instead of an element.
     * Default value is false.
     * @var boolean
     */
    public $attribute;

    /**
     * MAY be used only for an array definition. Signifies whether the array is wrapped
     * (for example, <books><book/><book/></books>) or unwrapped (<book/><book/>). Default value is
     * false. The definition takes effect only when defined alongside type being array
     * (outside the items).
     * @var boolean
     */
    public $wrapped;
}

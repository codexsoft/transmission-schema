<?php


namespace CodexSoft\Transmission\OpenApi2;

class InfoSchema implements AbstractOpenApiSchemaInterface
{
    /**
     * The title of the application.
     */
    public ?string $title = null;

    /**
     * A short description of the application. GFM syntax can be used for rich text representation.
     */
    public ?string $description = null;

    /**
     * The Terms of Service for the API.
     */
    public ?string $termsOfService = null;

    /**
     * The contact information for the exposed API.
     */
    public ContactSchema $contact;

    /**
     * The license information for the exposed API.
     */
    public LicenseSchema $license;

    /**
     * Provides the version of the application API (not to be confused by the specification version).
     */
    public ?string $version = null;

    public function __construct()
    {
        $this->contact = new ContactSchema();
        $this->license = new LicenseSchema();
    }

    public function exportToArray(): array
    {
        return [
            "title" => $this->title,
            "description" => $this->description,
            "version" => $this->version,
            "termsOfService" => $this->termsOfService,
        ];
    }
}

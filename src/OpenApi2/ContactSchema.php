<?php


namespace CodexSoft\Transmission\OpenApi2;


class ContactSchema implements AbstractOpenApiSchemaInterface
{
    /**
     * The identifying name of the contact person/organization.
     */
    public ?string $name = null;

    /**
     * The URL pointing to the contact information.
     */
    public ?string $url = null;

    /**
     * The email address of the contact person/organization.
     */
    public ?string $email = null;

    public function exportToArray(): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'email' => $this->email,
        ];
    }
}

<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface ReferencableElementInterface
{
    public function isReference(): bool;
    public function getReferencedClass(): ?string;
}

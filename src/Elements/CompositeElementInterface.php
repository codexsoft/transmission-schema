<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface CompositeElementInterface
{
    /**
     * @return string[]
     */
    public function collectMentionedSchemas(): array;
}

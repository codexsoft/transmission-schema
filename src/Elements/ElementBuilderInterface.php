<?php


namespace CodexSoft\Transmission\Schema\Elements;


interface ElementBuilderInterface
{
    public function build(): AbstractBaseElement;
}

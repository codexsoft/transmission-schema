<?php


namespace CodexSoft\Transmission\OpenApi2;


use Symfony\Component\HttpFoundation\Request;

class PostOperation extends Operation
{
    public string $method = Request::METHOD_POST;
}

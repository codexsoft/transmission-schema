<?php


namespace CodexSoft\Transmission\OpenApi2;


use Symfony\Component\HttpFoundation\Request;

class PostOperationSchema extends OperationSchema
{
    public string $method = Request::METHOD_POST;
}

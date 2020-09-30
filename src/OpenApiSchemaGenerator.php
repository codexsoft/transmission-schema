<?php


namespace CodexSoft\Transmission;


use EXSyst\Component\Swagger\Parameter;
use EXSyst\Component\Swagger\Path;
use EXSyst\Component\Swagger\Swagger;

class OpenApiSchemaGenerator
{
    public function generateOpenApiV2()
    {
        // todo: collect data (from router, from path)
        // convert to OpenApi document
        // generate JSON

        $collectedEndpoints = [];
        $collectedSchemaClasses = [];

        $swagger = new Swagger();
        $swagger
            ->setHost('api.pim.one')
            ->setBasePath('')
            ->merge([]);

        $swagger->getInfo()
            ->setTitle('GyperPIM API')
            ->setDescription('Интерфейс программного взаимодействия с базой данных продуктов.<br /><a href="https://api.pim.one/static/360-view/">Инструкция как добавить на свой сайт просмотр продукта в 360</a>')
            ->setVersion('1.0.0');

        (new Parameter())->setEnum();
        (new Path())->getOperations();

        $swagger->getPaths()->set();

        $swagger->toArray();
        //$swagger->getResponses()->set()

        $x = [
            "swagger" => "2.0",
            "info" => [
                "title" => "GyperPIM API",
                "description" => "\u0418\u043d\u0442\u0435\u0440\u0444\u0435\u0439\u0441 \u043f\u0440\u043e\u0433\u0440\u0430\u043c\u043c\u043d\u043e\u0433\u043e \u0432\u0437\u0430\u0438\u043c\u043e\u0434\u0435\u0439\u0441\u0442\u0432\u0438\u044f \u0441 \u0431\u0430\u0437\u043e\u0439 \u0434\u0430\u043d\u043d\u044b\u0445 \u043f\u0440\u043e\u0434\u0443\u043a\u0442\u043e\u0432.<br /><a href='https://api.pim.one/static/360-view/'>\u0418\u043d\u0441\u0442\u0440\u0443\u043a\u0446\u0438\u044f \u043a\u0430\u043a \u0434\u043e\u0431\u0430\u0432\u0438\u0442\u044c \u043d\u0430 \u0441\u0432\u043e\u0439 \u0441\u0430\u0439\u0442 \u043f\u0440\u043e\u0441\u043c\u043e\u0442\u0440 \u043f\u0440\u043e\u0434\u0443\u043a\u0442\u0430 \u0432 360</a>",
                "version" => "1.0.0",
            ],
            "host" => "api.pim.one",
            "basePath" => "",
            "schemes" => [
                "https"
            ],
            "consumes" => ["application/json"],
            "produces" => ["application/json"],
            "paths" => [
                "/v1/file/download/{id}" => [
                    "get" => [
                        "tags" => ["File"],
                        "summary" => "/v1/file/download/{id}",
                        "description" => "Download file",
                        "produces" => ["application/octet-stream"],
                        "parameters" => [
                            [
                                "name" => "id",
                                "in" => "path",
                                "description" => "",
                                "required" => true,
                                "type" => "integer",
                            ],
                        ],
                        "responses" => [
                            "200" => [
                                "description" => "File binary data",
                                "schema" => [
                                    "type" => "string",
                                    "format" => "binary",
                                ],
                            ],
                            "400" => [
                                "description" => "Generic Error",
                                "schema" => [
                                    "type" => "string",
                                ],
                            ],
                            "404" => [
                                "description" => "File not found",
                                "schema" => [
                                    "type" => "string",
                                ],
                            ],
                        ],
                    ],
                ],
                "/v1/image/download/{id}" => [
                    "get" => [
                        "tags" => ["File"],
                        "summary" => "/v1/image/download/{id}",
                        "description" => "Download image file. WEBP image format is ignores tuning parameters.",
                        "produces" => ["application/octet-stream"],
                        "parameters" => [
                            [
                                "name" => "id",
                                "in" => "path",
                                "description" => "",
                                "required" => true,
                                "type" => "integer",
                            ],
                            [
                                "name" => "w",
                                "in" => "query",
                                "description" => "Desired width of image",
                                "required" => false,
                                "type" => "integer",
                            ],
                            [
                                "name" => "h",
                                "in" => "query",
                                "description" => "Desired height of image",
                                "required" => false,
                                "type" => "integer",
                            ],
                            [
                                "name" => "or",
                                "in" => "query",
                                "description" => "Desired orientation of image",
                                "required" => false,
                                "type" => "integer",
                                "default" => "auto",
                                "enum" => [
                                    "auto",
                                    0,
                                    90,
                                    180,
                                    270
                                ],
                            ],
                            [
                                "name" => "crop",
                                "in" => "query",
                                "description" => "Crops the image to specific dimensions prior to any other resize operations. Required format: width,height,x,y. Example: crop=100,100,915,155",
                                "required" => false,
                                "type" => "integer",
                            ],
                            [
                                "name" => "disposition",
                                "in" => "query",
                                "description" => "Which Content-Disposition is needed",
                                "required" => false,
                                "type" => "string",
                                "default" => "inline",
                                "enum" => [
                                    "attachment",
                                    "inline"
                                ],
                            ],
                            [
                                "name" => "fit",
                                "in" => "query",
                                "description" => "Sets how the image is fitted to its target dimensions.",
                                "required" => false,
                                "type" => "string",
                                "enum" => [
                                    "contain",
                                    "max",
                                    "fill",
                                    "stretch",
                                    "crop"
                                ],
                            ],
                            [
                                "name" => "fm",
                                "in" => "query",
                                "description" => "Encodes the image to a specific format.",
                                "required" => false,
                                "type" => "string",
                                "default" => "jpg",
                                "enum" => [
                                    "jpg",
                                    "pjpg",
                                    "png",
                                    "gif",
                                    "webp"
                                ],
                            ],
                        ],
                        "responses" => [
                            "200" => [
                                "description" => "File binary data",
                                "schema" => [
                                    "type" => "string",
                                    "format" => "binary",
                                ],
                            ],
                            "400" => [
                                "description" => "Generic Error",
                                "schema" => [
                                    "type" => "string",
                                ],
                            ],
                            "404" => [
                                "description" => "File not found",
                                "schema" => [
                                    "type" => "string",
                                ],
                            ],
                        ],
                    ],
                ],
                "/v1/brand/getAll" => [
                    "post" => [
                        "tags" => ["Brand"],
                        "summary" => "/v1/brand/getAll",
                        "description" => "",
                        "parameters" => [
                            [
                                '$ref' => "#/parameters/App_Controller_Api_Brand_GetAll_BrandGetAllRequestForm",
                            ]
                        ],
                    ],
                ],
            ],
            "definitions" => [],
            "parameters" => [],
            "responses" => [],
        ];
    }
}

<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\JsonElement;
use CodexSoft\Transmission\OpenApi2\SwaggerSchema;
use CodexSoft\Transmission\OpenApi2\ParameterCollection;
use CodexSoft\Transmission\OpenApi2\ParameterSchema;
use CodexSoft\Transmission\OpenApi2\PathSchema;
use CodexSoft\Transmission\OpenApi2\PostOperationSchema;
use CodexSoft\Transmission\OpenApi2\ResponseSchema;
use CodexSoft\Transmission\OpenApiSchemaGeneratorTest\PetsAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class OpenApiSchemaGenerator
{
    /** @var string[]  */
    private $mentionedSchemaClasses = [];

    /**
     * Builds POST operation from HttpFoundation Route
     *
     * @param Route $route
     *
     * @return PostOperationSchema
     * @throws Exceptions\InvalidJsonSchemaException
     */
    private function routeToPostOperation(Route $route): PostOperationSchema
    {
        $post = new PostOperationSchema();

        $post->path = $route->getPath();

        $endpointClass = $route->getDefault('_controller');

        if (!\in_array(DocumentedJsonEndpointInterface::class, \class_implements($endpointClass), true)) {
            // todo: log that route controller does not implements DocumentedJsonEndpointInterface ?
            return $post;
            //throw new \Exception($endpointClass.' is not '.DocumentedJsonEndpointInterface::class);
        }

        $post->tags->replaceElements($endpointClass::getOpenApiTags());

        /*
         * Collecting request structure data
         */

        /** @var DocumentedJsonEndpointInterface $endpointClass */
        $inputSchema = $endpointClass::bodyInputSchema();
        $inputJson = new JsonElement($inputSchema);
        $inputJsonParameter = $inputJson->toOpenApiV2ParameterArray();

        \array_push($this->mentionedSchemaClasses, ...$inputJson->collectMentionedSchemas());

        //$parameter = ParameterSchema::createFromElement($inputJson);
        //$parameters = ParameterCollection::importFromElement($inputJson);
        //$post->parameters->add($parameter);
        $post->parametersArrayed = $inputJsonParameter;

        /*
         * Collecting operation responses
         * todo: if schema gathered, use ref, otherwise inline definition?
         */

        $outputSchema = $endpointClass::bodyOutputSchema();
        $outputJson = new JsonElement($outputSchema);

        \array_push($this->mentionedSchemaClasses, ...$outputJson->collectMentionedSchemas());

        $responsesSchemas = $endpointClass::allAlternativeResponses();

        /* adding normal output response to responses too */
        $responsesSchemas[Response::HTTP_OK] = $outputSchema;

        $routeResponses = [];
        foreach ($responsesSchemas as $httpCode => $responsesSchema) {
            $responseSchemaJson = new JsonElement($responsesSchema);
            if ($responseSchemaJson->getSchemaGatheredFromClass()) {
                $collectedSchemaClasses[] = $responseSchemaJson->getSchemaGatheredFromClass();
            }
            $responseParameter = $responseSchemaJson->toOpenApiV2ParameterArray();

            $rsp = new ResponseSchema();
            //$rsp->schema->
            //$rsp->schema
            $post->responses->set($httpCode, $rsp);

            //$routeResponses[$httpCode] = [
            //    'schema' => $responseParameter,
            //];
        }

        return $post;
    }

    public function generateOpenApiV2()
    {
        // todo: collect data (from router, from path). BUT. Path can be gathered from @Route annotation
        // convert to OpenApi document
        // generate JSON

        $routeCollection = new RouteCollection();
        $routeCollection->add('', (new Route('/pets'))->setDefault('_controller', PetsAction::class)->setMethods([Request::METHOD_POST]));

        $collectedSchemaClasses = [];

        $sw = new SwaggerSchema();
        $sw->swagger = '2.0';
        $sw->host = 'api.pim.one';
        $sw->basepath = '';
        $sw->info->title = 'GyperPIM API';
        $sw->info->description = 'Интерфейс программного взаимодействия с базой данных продуктов.<br /><a href="https://api.pim.one/static/360-view/">Инструкция как добавить на свой сайт просмотр продукта в 360</a>';
        $sw->info->version = '1.0.0';

        $sw->produces->add('application/json');
        $sw->consumes->add('application/json');
        $sw->schemes->add('https');

        /**
         * For JSON endpoints
         */
        foreach ($routeCollection as $routeItem) {

            if (!$sw->paths->containsKey($routeItem->getPath())) {
                $sw->paths->set($routeItem->getPath(), new PathSchema());
            }

            $pathSchema = $sw->paths->get($routeItem->getPath());

            foreach ($routeItem->getMethods() as $routeMethod) {
                switch ($routeMethod) {
                    case Request::METHOD_POST:
                        $pathSchema->post = $this->routeToPostOperation($routeItem);
                    // other methods currently are ignored
                    // todo: add support for GET methods
                }
            }
        }

        // generating all mentioned definitions

        /** @var mixed[] $definitions standalone definitions */
        //$definitions = [];
        //
        //foreach ($collectedSchemaClasses as $collectedSchemaClass) {
        //    if (!\in_array(JsonSchemaInterface::class, \class_implements($collectedSchemaClass), true)) {
        //        throw new \Exception('Schema definition for class '.$collectedSchemaClass.' could not be generated.');
        //    }
        //    /** @var JsonSchemaInterface $collectedSchemaClass */
        //    $definitionSchema = $collectedSchemaClass::createSchema();
        //    $definitionSchemaJson = new JsonElement($definitionSchema);
        //    $definitionParameter = $definitionSchemaJson->toOpenApiV2ParameterArray();
        //    /*
        //     * #/definitions/App_Controller_Api_Form_BrandForm
        //     */
        //
        //    $definitions[$collectedSchemaClass] = $definitionParameter;
        //}

        $result = $sw->exportToArray();

        //(new Parameter())->setEnum();
        //(new Path())->getOperations();

        //$swagger->getPaths()->set();

        //$res2 = $swagger->toArray();
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

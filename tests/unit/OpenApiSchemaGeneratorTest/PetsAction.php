<?php

namespace CodexSoft\Transmission\OpenApiSchemaGeneratorTest;

use CodexSoft\Transmission\AbstractJsonController;
use CodexSoft\Transmission\Accept;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("pets")
 */
class PetsAction extends AbstractJsonController
{
    /**
     * @inheritDoc
     */
    public static function bodyInputSchema(): array
    {
        return [
            'name' => Accept::string()->optional(),
        ];
    }

    public static function alternativeResponses(): array
    {
        return [
            Response::HTTP_NOT_FOUND => [
                'message' => Accept::string(),
            ],
            //Response::HTTP_BAD_REQUEST => 'test override',
            //Response::HTTP_NOT_FOUND => Accept::json([
            //    'message' => Accept::string(),
            //]),
        ];
    }

    public function handle(array $data, array $extraData = []): Response
    {
        return new JsonResponse(['data' => []]);
    }

    /**
     * @inheritDoc
     */
    public static function bodyOutputSchema(): array
    {
        return [
            'pets' => Accept::collection(PetData::class),
            'pagination' => Accept::json([
                'perPage' => Accept::integer(),
                'currentPage' => Accept::integer(),
                'totalCount' => Accept::integer(),
                'totalPages' => Accept::integer(),
            ]),
        ];
    }
}

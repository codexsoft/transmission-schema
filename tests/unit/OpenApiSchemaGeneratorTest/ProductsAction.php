<?php

namespace CodexSoft\Transmission\OpenApiSchemaGeneratorTest;

use CodexSoft\Transmission\AbstractJsonController;
use CodexSoft\Transmission\Accept;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("product/getAll")
 */
class ProductsAction extends AbstractJsonController
{
    /**
     * @inheritDoc
     */
    protected function inputSchema(): array
    {
        return [
            'page' => Accept::integer()->defaultValue(1),
            'perPage' => Accept::integer()->defaultValue(50),
            'name' => Accept::string('Название или часть названия')->optional(),
            'articul' => Accept::string('Артикул продукта (или его часть)')->optional(),
            'barcode' => Accept::string('Штрихкод продукта (или его часть)')->optional(),
            'marketplaceIdYandexMarket' => Accept::string(),
            'marketplaceIdOzon' => Accept::string(),
            'marketplaceIdBeru' => Accept::string(),
            'marketplaceIdGyper' => Accept::string(),
            //'warnings' => Accept::string(), // порог имеющегося количество предупреждений
            'product' => Accept::string(), // id продукта
            'brand' => Accept::string(),
            'category' => Accept::string(),
            //'type' => Accept::string(),
            // todo: order by?
        ];
    }

    public static function alternativeResponses(): array
    {
        return [
            Response::HTTP_NOT_FOUND => Accept::json([
                'message' => Accept::string(),
            ]),
        ];
    }

    public function handle(array $data, array $extraData = []): Response
    {
        return new JsonResponse(['data' => []]);
    }

    /**
     * @inheritDoc
     */
    protected function outputSchema(): array
    {
        return [
            'products' => Accept::collection(ProductData::class),
            'pagination' => Accept::json([
                'perPage' => Accept::integer(),
                'currentPage' => Accept::integer(),
                'totalCount' => Accept::integer(),
                'totalPages' => Accept::integer(),
            ]),
        ];
    }
}

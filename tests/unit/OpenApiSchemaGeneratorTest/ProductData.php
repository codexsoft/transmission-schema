<?php


namespace CodexSoft\Transmission\OpenApiSchemaGeneratorTest;


use App\Database\Main\Doctrine\Model\File;
use App\Database\Main\Doctrine\Model\Product;
use CodexSoft\Transmission\Accept;
use CodexSoft\Transmission\JsonSchemaInterface;

class ProductData implements JsonSchemaInterface
{
    /**
     * @inheritDoc
     */
    public static function createSchema(): array
    {
        return [
            'id' => Accept::id('ID продукта в системе PIM')->notBlank(),
            'attributes' => Accept::collection(UnifiedProductAttributeData::class, 'Атрибуты продукта')->notBlank(),
            'brand' => Accept::json(BrandData::class, 'Информация по брэнду, к которому относится продукт')->nullable(),
            'brandId' => Accept::id('ID брэнда, к которому относится продукт')->nullable(),
            'description' => Accept::text()->nullable(),
            'mainPicture' => Accept::json(ProductPictureData::class, 'Основное изображение продукта')->nullable(),
            'mainPictureId' => Accept::id('ID основного изображения продукта')->nullable(),
            'marketplaceIdBeru' => Accept::text('ID продукта в Беру.ру')->nullable(),
            'marketplaceIdGyper' => Accept::text('ID продукта в ГИПЕР')->nullable(),
            'marketplaceIdOzon' => Accept::text('ID продукта в OZON')->nullable(),
            'marketplaceIdYandexMarket' => Accept::text('ID продукта в Яндекс.Маркет')->nullable(),
            'name' => Accept::text()->notBlank(),
            'productContentPacks' => Accept::collection(ProductContentPackData::class, 'Контентные связки продукта')->notBlank(),
            'productPictures' => Accept::collection(ProductPictureData::class, 'Изображения продукта')->notBlank(),
            'productBanners' => Accept::collection(ProductPictureData::class, 'Баннеры')->notBlank(),
            'productLifestyleImages' => Accept::collection(ProductPictureData::class, 'Изображения продукта внутри интерьера')->notBlank(),
            'productDefaultCategoryId' => Accept::integer('ID основной категории продукта (категория «по умолчанию»)')->nullable(),
            'productCategoriesIds' => Accept::collection(Accept::id(), 'Массив ID категорий, к которым относится продукт')->notBlank(),
            'productVideos' => Accept::collection(ProductVideoData::class, 'Видео продукта')->notBlank(),
            'vendorArticul' => Accept::text('Артикул в системе вендора')->nullable(),
            'vendorBarcode' => Accept::text('Штрихкод в системе вендора')->nullable(),
            'productCertificate' => Accept::json(ProductCertificateData::class)->label('Сертификат продукта')->nullable(),
        ];
    }
}

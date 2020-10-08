<?php


namespace CodexSoft\Transmission\Schema\Elements;

use Symfony\Component\Validator\Constraints;

class IntegerElement extends NumberElement
{
    protected ?array $acceptedPhpTypes = ['integer'];
    protected string $openApiType = 'integer';
    protected $example = 42;

    /**
     * @param $data
     *
     * @return int
     * @inheritDoc
     */
    public function doNormalizeData($data): ?int
    {
        $data = parent::doNormalizeData($data);
        return (int) $data;
    }

    protected function generateFormalSfConstraints(): array
    {
        $constraints = parent::generateFormalSfConstraints();
        $constraints[] = new Constraints\Type(['type' => 'integer']);
        return $constraints;
    }
}

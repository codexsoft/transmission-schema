<?php


namespace CodexSoft\Transmission\Schema\Elements;

//use Symfony\Component\Validator\Constraints;

class IntegerElement extends NumberElement
{
    protected ?array $acceptedPhpTypes = ['integer'];
    protected mixed $example = 42;

    /**
     * @param $data
     *
     * @return int
     */
    public function doNormalizeData(mixed $data): ?int
    {
        $data = parent::doNormalizeData($data);
        return (int) $data;
    }

    //protected function generateFormalSfConstraints(): array
    //{
    //    $constraints = parent::generateFormalSfConstraints();
    //    $constraints[] = new Constraints\Type(['type' => 'integer']);
    //    return $constraints;
    //}
}

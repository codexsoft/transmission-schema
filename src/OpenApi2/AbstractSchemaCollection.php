<?php


namespace CodexSoft\Transmission\OpenApi2;


use Doctrine\Common\Collections\ArrayCollection;

class AbstractSchemaCollection extends ArrayCollection
{
    public function toArrayRecursive(): array
    {
        $data = [];
        foreach ($this->getValues() as $value) {
            if ($value instanceof AbstractOpenApiSchemaInterface) {
                $data[] = $value->exportToArray(); // todo: keys?
            }
        }

        return $data;
    }
}

<?php


namespace CodexSoft\Transmission\OpenApi2;


use Doctrine\Common\Collections\ArrayCollection;

class AbstractSchemaCollection extends ArrayCollection
{

    /**
     * @param array $elements
     *
     * @return static
     */
    public function replaceElements(array $elements)
    {
        $this->clear();
        foreach ($elements as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

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

<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Exceptions\GenericException;
use Symfony\Component\HttpFoundation\Request;

class Transmission
{

    /**
     * @param Request $request
     *
     * @param bool $replace replace request with decoded JSON content
     *
     * @return array
     * @throws GenericException
     */
    public static function decodeJsonInRequest(Request $request, bool $replace = true): array
    {
        if ($request->getContentType() !== 'json' || !$request->getContent()) {
            throw new GenericException('Invalid JSON body or empty content');
        }

        try {
            $data = \json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new GenericException('Malformed JSON body: '.\json_last_error_msg());
        }

        $result = \is_array($data) ? $data : [];

        if ($replace) {
            $request->request->replace($result);
        }

        return $result;
    }
}

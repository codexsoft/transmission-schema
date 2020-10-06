<?php


namespace CodexSoft\Transmission;


use CodexSoft\Transmission\Elements\AbstractElement;
use CodexSoft\Transmission\Elements\JsonElement;
use CodexSoft\Transmission\Exceptions\IncompatibleInputDataTypeException;
use CodexSoft\Transmission\Exceptions\GenericException;
use CodexSoft\Transmission\Exceptions\InvalidJsonSchemaException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractJsonController implements JsonEndpointInterface
{
    protected Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        if ($request === null) {
            throw new \InvalidArgumentException('RequestStack is empty, failed to get current Request!');
        }
        $this->request = $request;
        $this->init();
    }

    /**
     * Expected request JSON schema
     * @return AbstractElement[]
     */
    abstract public static function bodyInputSchema(): array;

    /**
     * Implement this method to handle input JSON data
     *
     * @param array $data
     * @param array $extraData
     *
     * @return Response
     */
    abstract protected function handle(array $data, array $extraData = []): Response;

    protected function init(): void
    {
    }

    protected function beforeHandle(): void
    {
    }

    protected function afterHandle(Response $response): void
    {
    }

    /**
     * You can also throw exception in overriden method and handle it by Subscriber, for example.
     * @param InvalidJsonSchemaException $e
     *
     * @return Response
     */
    protected function onInvalidBodyInputSchema(InvalidJsonSchemaException $e): Response
    {
        return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function onViolationsDetected(ConstraintViolationListInterface $violations): Response
    {
        $violationsData = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $violationsData[] = $violation->getPropertyPath().': '.$violation->getInvalidValue().': '.$violation->getMessage();
        }

        return new JsonResponse([
            'message' => 'Invalid request data: '.\implode(', ', $violationsData),
            'data' => $violationsData,
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * todo: current implementation prevents to calculate violations by validator in case of normalization fails.
     *
     * @param IncompatibleInputDataTypeException $e
     *
     * @return Response
     */
    protected function onNormalizationFail(IncompatibleInputDataTypeException $e): Response
    {
        return new JsonResponse([], Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @return Response
     */
    public function __invoke(): Response
    {
        try {
            Transmission::decodeJsonInRequest($this->request);
        } catch (GenericException $e) {
            return new JsonResponse([], Response::HTTP_NOT_ACCEPTABLE);
        }

        $inputData = $this->request->request->all();

        try {
            $schema = (new JsonElement(static::bodyInputSchema()));
        } catch (InvalidJsonSchemaException $e) {
            return $this->onInvalidBodyInputSchema($e);
        }

        try {
            $validationResult = $schema->getValidatedNormalizedData($inputData);
        } catch (IncompatibleInputDataTypeException $e) {
            return $this->onNormalizationFail($e);
        }

        if ($validationResult->getViolations()->count()) {
            return $this->onViolationsDetected($validationResult->getViolations());
        }

        $this->beforeHandle();
        $response = $this->handle($validationResult->getData(), $validationResult->getExtraData());
        $this->afterHandle($response);

        return $response;
    }
}

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
use Symfony\Component\Validator\Validation;

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
            $schema = (new JsonElement($this->bodyInputSchema()));
        } catch (InvalidJsonSchemaException $e) {
            return new JsonResponse([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            [$normalizedData, $extraData] = $schema->normalizeDataReturningNormalizedAndExtraData($inputData);
        } catch (IncompatibleInputDataTypeException $e) {
            return new JsonResponse([], Response::HTTP_NOT_ACCEPTABLE);
        }

        $sfConstraints = $schema->compileToSymfonyValidatorConstraint();
        $validator = Validation::createValidator();
        $violations = $validator->validate($normalizedData, $sfConstraints);

        if ($violations->count()) {

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

        $this->beforeHandle();
        $response = $this->handle($normalizedData, $extraData);
        $this->afterHandle($response);

        return $response;
    }
 }

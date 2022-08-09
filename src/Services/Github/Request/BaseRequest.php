<?php

namespace App\Services\Github\Request;



namespace App\Services\Github\Request;

use App\Services\Github\Exceptions\RequestValidationException;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseRequest
{
    private ReflectionClass $reflection;
    private Request $request;

    public function __construct(private readonly ValidatorInterface $validator, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->reflection = new ReflectionClass($this);
        $this->populate();
        if ($this->isAutoValidateRequest()) {
            $this->validate();
        }
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->getRequest()->get($key, $default);
    }

    /** @return array<string,mixed> */
    public function validated(): array
    {
        $properties = $this->reflection->getProperties(ReflectionProperty::IS_PROTECTED);
        $values = [];
        foreach ($properties as $property) {
            $key = $property->getName();
            $value = $property->getValue($this);
            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * @throws RequestValidationException
     */
    public function validate(): void
    {
        $errors = $this->validator->validate($this);

        $messages = ['status' => 'error', 'message' => 'Validation failed', 'errors' => []];

        /* @var ConstraintViolation */
        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            throw new RequestValidationException(json_encode($messages),422);
        }
    }

    protected function populate(): void
    {
        foreach ($this->getRequestParams() as $property => $value) {
            if (property_exists($this, $property)) {
                $type = $this->getAttributes($property)['Type'][0];
                $value = $this->convertStringToCorrectType($value, $type);
                $this->{$property} = $value;
            }
        }
    }

    protected function isAutoValidateRequest(): bool
    {
        return false;
    }

    protected function getRequestParams(): array
    {
        $request = $this->getRequest();
        $queryParams = $request->query->all();
        try {
            $bodyParams = $request->toArray();
        } catch (JsonException $e) {
            $bodyParams = [];
        }

        return array_merge($queryParams, $bodyParams);
    }

    protected function convertStringToCorrectType(mixed $value, string $propertyType): mixed
    {
        if ('true' === $value || 'false' === $value) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        return match ($propertyType) {
            'integer' => (int) $value,
            'string' => (string) $value,
            'bool' => (bool) $value,
            default => $value
        };
    }

    private function getAttributes(string $property): array
    {
        $data = [];
        $propertyAttributes = $this->reflection->getProperty($property)->getAttributes();
        foreach ($propertyAttributes as $attribute) {
            $namingParts = explode('\\', $attribute->getName());
            $name = end($namingParts);
            $data[$name] = $attribute->getArguments();
        }

        return $data;
    }
}

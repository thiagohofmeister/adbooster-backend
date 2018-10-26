<?php

namespace App\Service\Base;

use JsonSchema;
use THS\Utils\Converter\Type\Json;

/**
 * Validador de esquema.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Validator
{
    const SCHEMA_PATH = __DIR__ . '/../../../schema/';

    /** @var JsonSchema\Validator */
    private $jsonSchemaValidator;

    /**
     * @param JsonSchema\Validator $jsonSchemaValidator
     */
    public function __construct(JsonSchema\Validator $jsonSchemaValidator)
    {
        $this->jsonSchemaValidator = $jsonSchemaValidator;
    }

    /**
     * Valida se os dados estão válidos de acordo com o esquema.
     *
     * @param array $data
     * @param string $schema
     *
     * @return bool
     *
     * @throws \Exception
     * @throws \THS\Utils\Converter\Exception\JsonException
     */
    public function validate(array $data, string $schema): bool
    {
        if (!file_exists(self::SCHEMA_PATH . $schema)) {
            throw new \Exception('Arquivo de schema não encontrado.');
        }

        $this->jsonSchemaValidator->validate(json_decode(Json::fromArray($data)), $this->loadSchema($schema), JsonSchema\Constraints\Constraint::CHECK_MODE_COERCE_TYPES);

        return $this->jsonSchemaValidator->isValid();
    }

    /**
     * Retorna os erros de validação.
     *
     * @return array
     */
    public function getErrors(): array
    {
        $errors = [
            'message' => 'Validation failed',
            'data' => [],
        ];

        foreach ($this->jsonSchemaValidator->getErrors() as $error) {
            $errors['data'][] = $error;
        }

        return $errors;
    }

    /**
     * Carrega o arquivo de schema.
     *
     * @param string $file
     *
     * @return array
     */
    private function loadSchema(string $file): array
    {
        return ['$ref' => 'file://' . self::SCHEMA_PATH . $file];
    }
}

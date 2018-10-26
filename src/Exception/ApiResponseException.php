<?php

namespace App\Exception;

use THS\Utils\Enum\HttpStatusCode;
use Throwable;

/**
 * Erros de resposta da API.
 * A mensagem da exceção será a mensagem do retorno e o status será o http status code.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class ApiResponseException extends \Exception
{
    /** @var array */
    private $headers;

    /**
     * @param string $message
     * @param int|HttpStatusCode $code
     * @param array $headers
     */
    public function __construct(string $message = "", $code = 0, array $headers = [])
    {
        if ($code instanceof HttpStatusCode) {
            $code = $code->value();
        }

        $this->headers = $headers;

        parent::__construct($message, $code);
    }

    /**
     * Retorna a propriedade {@see ApiResponseException::$headers}.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}

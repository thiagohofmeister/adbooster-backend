<?php

namespace App\Service\Base;

use THS\Utils;

/**
 * Modelagem da resposta dos métodos dos módulos.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Response
{
    /** @var array */
    private $data;

    /** @var Utils\Enum\HttpStatusCode */
    private $statusCode;

    /** @var array */
    private $headers;

    /**
     * Cria uma resposta.
     *
     * @param array $data
     * @param Utils\Enum\HttpStatusCode $statusCode
     * @param array $headers
     *
     * @return Response
     */
    public static function create(array $data, Utils\Enum\HttpStatusCode $statusCode, array $headers = []): Response
    {
        return (new Response)
            ->setData($data)
            ->setStatusCode($statusCode)
            ->setHeaders($headers);
    }

    /**
     * Retorna a propriedade {@see Response::$data}.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Define a propriedade {@see Response::$data}.
     *
     * @param array $data
     *
     * @return static|Response
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Response::$statusCode}.
     *
     * @return Utils\Enum\HttpStatusCode
     */
    public function getStatusCode(): Utils\Enum\HttpStatusCode
    {
        return $this->statusCode;
    }

    /**
     * Define a propriedade {@see Response::$statusCode}.
     *
     * @param Utils\Enum\HttpStatusCode $statusCode
     *
     * @return static|Response
     */
    public function setStatusCode(Utils\Enum\HttpStatusCode $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Response::$headers}.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Define a propriedade {@see Response::$headers}.
     *
     * @param array $headers
     *
     * @return static|Response
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }
}

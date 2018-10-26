<?php

namespace App\Exception\Repository;

/**
 * Erros quando faltam dados para a realização da operação no repositório
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class DataNotFoundException extends \Exception
{
    /** @var array Dados não encontrados */
    private $data;

    /**
     * @param array $data Dados não encontrados
     * @param string $message Mensagem
     * @param int $code Código da exceção
     * @param \Exception $previous Exceção prevista
     */
    public function __construct($data, $message = 'Dados não encontrados', $code = 0, \Exception $previous = null)
    {
        $this->data = $data;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Retorna os dados não encontrados.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Define os dados não encontrados.
     *
     * @param array $data
     *
     * @return DataNotFoundException
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Adiciona um dado não encontrado.
     *
     * @param string $dataKey
     * @param string $dataDescription
     *
     * @return DataNotFoundException
     */
    public function addData($dataKey, $dataDescription)
    {
        $this->data[$dataKey] = $dataDescription;
        return $this;
    }
}
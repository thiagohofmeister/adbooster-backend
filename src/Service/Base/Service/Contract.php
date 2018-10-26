<?php

namespace App\Service\Base\Service;

use Slim\Http\Request;

/**
 * Abstração de serviços.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
abstract class Contract
{
    /** @var Request */
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Retorna a propriedade {@see Contract::$request}.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}

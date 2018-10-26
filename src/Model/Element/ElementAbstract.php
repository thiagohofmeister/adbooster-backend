<?php

namespace App\Model\Element;

/**
 * Elemento básico que contempla os atributos e funções que todos os elementos utilizam.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
abstract class ElementAbstract
{
    /**
     * Converte o elemento para um array esperado pelo documento.
     *
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * Cria um elemento a partir dos dados do documento.
     *
     * @param array $array
     *
     * @return static|ElementAbstract
     */
    abstract public static function fromArray(array $array);
}

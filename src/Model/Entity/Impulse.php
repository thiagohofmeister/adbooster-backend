<?php

namespace App\Model\Entity;

/**
 * Modelagem de impulsos dos anúncios.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Impulse extends EntityAbstract
{
    /** @var \DateTime */
    private $created;

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        /**
         * @todo Implement method toArray.
         */
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        /**
         * @todo Implement method fromArray.
         */
    }
}
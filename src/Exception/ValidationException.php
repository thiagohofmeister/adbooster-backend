<?php

namespace App\Exception;

use Throwable;

/**
 * Exceção lançada ao ocorrer uma falhar em alguma validação.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class ValidationException extends \Exception
{
    /** @var string Propriedade deve ser maior do que determinado valor. */
    const GREATER_THAN = 'greaterThan';

    /** @var string Propriedade deve ser igual ao determinado valor. */
    const EQUALS = 'equals';

    /** @var string Propriedade deve ser menor do que determinado valor. */
    const LESS_THAN = 'lessThan';

    /** @var string Propriedade que causou a falha na validação. */
    private $property;

    /** @var string Motivo da falha de validação. */
    private $constraint;

    /** @var mixed Valor do erro de validação. Exemplo: caso a validação seja maxLenght o valor pode ser 44. */
    private $constraintValue;

    public function __construct(string $property, string $constraint, $constraintValue = null, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->property = $property;
        $this->constraint = $constraint;
        $this->constraintValue = $constraintValue;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Retorna a propriedade {@see ValidationException::$property}.
     *
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * Define a propriedade {@see ValidationException::$property}.
     *
     * @param string $property
     *
     * @return static|ValidationException
     */
    public function setProperty(string $property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Retorna a propriedade {@see ValidationException::$constraint}.
     *
     * @return string
     */
    public function getConstraint(): string
    {
        return $this->constraint;
    }

    /**
     * Define a propriedade {@see ValidationException::$constraint}.
     *
     * @param string $constraint
     *
     * @return static|ValidationException
     */
    public function setConstraint(string $constraint)
    {
        $this->constraint = $constraint;

        return $this;
    }

    /**
     * Retorna a propriedade {@see ValidationException::$constraintValue}.
     *
     * @return mixed
     */
    public function getConstraintValue()
    {
        return $this->constraintValue;
    }

    /**
     * Define a propriedade {@see ValidationException::$constraintValue}.
     *
     * @param mixed $constraintValue
     *
     * @return static|ValidationException
     */
    public function setConstraintValue($constraintValue)
    {
        $this->constraintValue = $constraintValue;

        return $this;
    }
}

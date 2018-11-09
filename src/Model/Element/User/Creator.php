<?php

namespace App\Model\Element\User;

/**
 * Elemento de usuário criador de anúncio.
 *
 * @author Thiago Hofmeister <thiago.souza@moovin.com.br>
 */
class Creator extends Standard
{
    /** @var string */
    private $name;

    /** @var string */
    private $image;

    /**
     * Retorna a propriedade {@see Standard::$name}.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Define a propriedade {@see Standard::$name}.
     *
     * @param string $name
     *
     * @return static|Creator
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Standard::$image}.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Define a propriedade {@see Standard::$image}.
     *
     * @param string $image
     *
     * @return static|Creator
     */
    public function setImage(string $image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'name' => $this->getName(),
            'image' => $this->getImage(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        return parent::fromArray($array)
            ->setName($array['name'])
            ->setImage($array['image']);
    }
}

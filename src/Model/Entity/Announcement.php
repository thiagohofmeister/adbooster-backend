<?php

namespace App\Model\Entity;

use App\Enum;
use App\Model\Element;
use THS\Utils\Date;

/**
 * Modelagem do anúncio.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class Announcement extends EntityAbstract
{
    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var Element\User\Creator Criador do anúncio. */
    private $creator;

    /** @var float */
    private $previousPrice;

    /** @var float */
    private $currentPrice;

    /** @var float */
    private $impulsePayoutLimit;

    /** @var int */
    private $stock;

    /** @var array */
    private $images;

    /** @var \DateTime */
    private $created;

    /** @var \DateTime */
    private $updated;

    /** @var Enum\Announcement\Status */
    private $status;

    /** @var Element\Impulse[] */
    private $impulses;

    /** @var Enum\Announcement\Type */
    private $type;

    /**
     * Retorna a propriedade {@see Announcement::$title}.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Define a propriedade {@see Announcement::$title}.
     *
     * @param string $title
     *
     * @return static|Announcement
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$description}.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Define a propriedade {@see Announcement::$description}.
     *
     * @param string $description
     *
     * @return static|Announcement
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$creator}.
     *
     * @return Element\User\Creator
     */
    public function getCreator(): Element\User\Creator
    {
        return $this->creator;
    }

    /**
     * Define a propriedade {@see Announcement::$creator}.
     *
     * @param Element\User\Creator $creator
     *
     * @return static|Announcement
     */
    public function setCreator(Element\User\Creator $creator)
    {
        $this->creator = $creator;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$previousPrice}.
     *
     * @return float
     */
    public function getPreviousPrice(): float
    {
        return $this->previousPrice;
    }

    /**
     * Define a propriedade {@see Announcement::$previousPrice}.
     *
     * @param float $previousPrice
     *
     * @return static|Announcement
     */
    public function setPreviousPrice(float $previousPrice)
    {
        $this->previousPrice = $previousPrice;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$currentPrice}.
     *
     * @return float
     */
    public function getCurrentPrice(): float
    {
        return $this->currentPrice;
    }

    /**
     * Define a propriedade {@see Announcement::$currentPrice}.
     *
     * @param float $currentPrice
     *
     * @return static|Announcement
     */
    public function setCurrentPrice(float $currentPrice)
    {
        $this->currentPrice = $currentPrice;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$impulsePayoutLimit}.
     *
     * @return float
     */
    public function getImpulsePayoutLimit(): float
    {
        return $this->impulsePayoutLimit;
    }

    /**
     * Define a propriedade {@see Announcement::$impulsePayoutLimit}.
     *
     * @param float $impulsePayoutLimit
     *
     * @return static|Announcement
     */
    public function setImpulsePayoutLimit(float $impulsePayoutLimit)
    {
        $this->impulsePayoutLimit = $impulsePayoutLimit;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$stock}.
     *
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * Define a propriedade {@see Announcement::$stock}.
     *
     * @param int $stock
     *
     * @return static|Announcement
     */
    public function setStock(int $stock)
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$images}.
     *
     * @return array
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * Define a propriedade {@see Announcement::$images}.
     *
     * @param array $images
     *
     * @return static|Announcement
     */
    public function setImages(?array $images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$created}.
     *
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * Define a propriedade {@see Announcement::$created}.
     *
     * @param \DateTime $created
     *
     * @return static|Announcement
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$updated}.
     *
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * Define a propriedade {@see Announcement::$updated}.
     *
     * @param \DateTime $updated
     *
     * @return static|Announcement
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$status}.
     *
     * @return Enum\Announcement\Status
     */
    public function getStatus(): Enum\Announcement\Status
    {
        return $this->status;
    }

    /**
     * Define a propriedade {@see Announcement::$status}.
     *
     * @param Enum\Announcement\Status $status
     *
     * @return static|Announcement
     */
    public function setStatus(Enum\Announcement\Status $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$impulses}.
     *
     * @return Element\Impulse[]
     */
    public function getImpulses()
    {
        return $this->impulses;
    }

    /**
     * Define a propriedade {@see Announcement::$impulses}.
     *
     * @param Element\Impulse[] $impulses
     *
     * @return static|Announcement
     */
    public function setImpulses($impulses)
    {
        $this->impulses = $impulses;
        return $this;
    }

    /**
     * Retorna a propriedade {@see Announcement::$type}.
     *
     * @return Enum\Announcement\Type
     */
    public function getType(): Enum\Announcement\Type
    {
        return $this->type;
    }

    /**
     * Define a propriedade {@see Announcement::$type}.
     *
     * @param Enum\Announcement\Type $type
     *
     * @return static|Announcement
     */
    public function setType(Enum\Announcement\Type $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $impulses = [];
        foreach ($this->impulses as $impulse) {
            $impulses[] = $impulse->toArray();
        }

        $toArray = [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'creator' => $this->getCreator()->toArray(),
            'previousPrice' => $this->getPreviousPrice(),
            'currentPrice' => $this->getCurrentPrice(),
            'impulsePayoutLimit' => $this->getImpulsePayoutLimit(),
            'stock' => $this->getStock(),
            'images' => $this->getImages(),
            'created' => $this->getCreated()->format(Date::JAVASCRIPT_ISO_FORMAT),
            'updated' => $this->getUpdated()->format(Date::JAVASCRIPT_ISO_FORMAT),
            'status' => $this->getStatus()->value(),
            'impulses' => $impulses,
            'type' => $this->getType()->value()
        ];

        if (!empty($this->getId())) {
            $toArray['_id'] = $this->getId();
        }

        return $toArray;
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $array)
    {
        $impulses = [];
        foreach ($array['impulses'] as $impulse) {
            $impulses[] = Element\Impulse::fromArray((array) $impulse);
        }

        return (new static($array['_id']))
            ->setTitle($array['title'])
            ->setDescription($array['description'])
            ->setCreator(Element\User\Creator::fromArray((array) $array['creator']))
            ->setPreviousPrice($array['previousPrice'])
            ->setCurrentPrice($array['currentPrice'])
            ->setImpulsePayoutLimit($array['impulsePayoutLimit'])
            ->setStock($array['stock'])
            ->setImages(!empty($array['images']) ? (array) $array['images'] : null)
            ->setCreated(new \DateTime($array['created']))
            ->setUpdated(new \DateTime($array['updated']))
            ->setStatus(Enum\Announcement\Status::memberByValue($array['status']))
            ->setImpulses($impulses)
            ->setType(Enum\Announcement\Type::memberByValue($array['type']));
    }
}

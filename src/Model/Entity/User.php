<?php

namespace App\Model\Entity;

use App\Enum;
use THS\Utils\Date;
use App\Model\Element;

/**
 * Representa a modelagem dos usuários do sistema.
 *
 * @author Thiago Hofmeister <thiago.hofmeister@gmail.com>
 */
class User extends EntityAbstract
{
    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /** @var string */
    private $phone;

    /** @var string */
    private $mobile;

    /** @var string */
    private $password;

    /** @var string CPF. */
    private $documentNumber;

    /** @var \DateTime */
    private $dob;

    /** @var string RG. */
    private $personalDocument;

    /** @var Element\User\Standard Usuário que fez o convite para o site. */
    private $invitedBy;

    /** @var Element\User\Authentication Autenticação.  */
    private $authentication;

    /** @var \DateTime Data de cadastro. */
    private $date;

    /** @var Enum\Gender */
    private $gender;

    /** @var Element\User\Address */
    private $billingAddress;

    /** @var Element\User\Address[] */
    private $shippingAddresses;

    /** @var double Dinheiro do usuário. */
    private $coins;

    /**
     * Retorna a propriedade {@see User::$name}.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Define a propriedade {@see User::$name}.
     *
     * @param string $name
     *
     * @return static|User
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$email}.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Define a propriedade {@see User::$email}.
     *
     * @param string $email
     *
     * @return static|User
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$phone}.
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Define a propriedade {@see User::$phone}.
     *
     * @param string $phone
     *
     * @return static|User
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$mobile}.
     *
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * Define a propriedade {@see User::$mobile}.
     *
     * @param string $mobile
     *
     * @return static|User
     */
    public function setMobile(string $mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$password}.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Define a propriedade {@see User::$password}.
     *
     * @param string $password
     *
     * @return static|User
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$documentNumber}.
     *
     * @return string
     */
    public function getDocumentNumber(): string
    {
        return $this->documentNumber;
    }

    /**
     * Define a propriedade {@see User::$documentNumber}.
     *
     * @param string $documentNumber
     *
     * @return static|User
     */
    public function setDocumentNumber(string $documentNumber)
    {
        $this->documentNumber = $documentNumber;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$dob}.
     *
     * @return \DateTime
     */
    public function getDob(): \DateTime
    {
        return $this->dob;
    }

    /**
     * Define a propriedade {@see User::$dob}.
     *
     * @param \DateTime $dob
     *
     * @return static|User
     */
    public function setDob(\DateTime $dob)
    {
        $this->dob = $dob;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$personalDocument}.
     *
     * @return string
     */
    public function getPersonalDocument(): string
    {
        return $this->personalDocument;
    }

    /**
     * Define a propriedade {@see User::$personalDocument}.
     *
     * @param string $personalDocument
     *
     * @return static|User
     */
    public function setPersonalDocument(string $personalDocument)
    {
        $this->personalDocument = $personalDocument;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$invitedBy}.
     *
     * @return Element\User\Standard
     */
    public function getInvitedBy(): ?Element\User\Standard
    {
        return $this->invitedBy;
    }

    /**
     * Define a propriedade {@see User::$invitedBy}.
     *
     * @param Element\User\Standard $invitedBy
     *
     * @return static|User
     */
    public function setInvitedBy(?Element\User\Standard $invitedBy)
    {
        $this->invitedBy = $invitedBy;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$authentication}.
     *
     * @return Element\User\Authentication
     */
    public function getAuthentication(): ?Element\User\Authentication
    {
        return $this->authentication;
    }

    /**
     * Define a propriedade {@see User::$authentication}.
     *
     * @param Element\User\Authentication $authentication
     *
     * @return static|User
     */
    public function setAuthentication(?Element\User\Authentication $authentication)
    {
        $this->authentication = $authentication;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$date}.
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * Define a propriedade {@see User::$date}.
     *
     * @param \DateTime $date
     *
     * @return static|User
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$gender}.
     *
     * @return Enum\Gender
     */
    public function getGender(): ?Enum\Gender
    {
        return $this->gender;
    }

    /**
     * Define a propriedade {@see User::$gender}.
     *
     * @param Enum\Gender $gender
     *
     * @return static|User
     */
    public function setGender(?Enum\Gender $gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$billingAddress}.
     *
     * @return Element\User\Address
     */
    public function getBillingAddress(): ?Element\User\Address
    {
        return $this->billingAddress;
    }

    /**
     * Define a propriedade {@see User::$billingAddress}.
     *
     * @param Element\User\Address $billingAddress
     *
     * @return static|User
     */
    public function setBillingAddress(?Element\User\Address $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$shippedAddresses}.
     *
     * @return Element\User\Address[]
     */
    public function getShippingAddresses()
    {
        return $this->shippingAddresses;
    }

    /**
     * Define a propriedade {@see User::$shippedAddresses}.
     *
     * @param Element\User\Address[] $shippingAddresses
     *
     * @return static|User
     */
    public function setShippingAddresses($shippingAddresses)
    {
        $this->shippingAddresses = $shippingAddresses;
        return $this;
    }

    /**
     * Retorna a propriedade {@see User::$coins}.
     *
     * @return float
     */
    public function getCoins(): float
    {
        return $this->coins;
    }

    /**
     * Define a propriedade {@see User::$coins}.
     *
     * @param float $coins
     *
     * @return static|User
     */
    public function setCoins(float $coins)
    {
        $this->coins = $coins;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $shippingAddresses = [];
        foreach ($this->getShippingAddresses() as $shippingAddress) {
            $shippingAddresses[] = $shippingAddress->toArray();
        }

        $toArray = [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'mobile' => $this->getMobile(),
            'password' => $this->getPassword(),
            'documentNumber' => $this->getDocumentNumber(),
            'dob' => $this->getDob(),
            'personalDocument' => $this->getPersonalDocument(),
            'invitedBy' => !empty($this->getInvitedBy()) ? $this->getInvitedBy()->toArray() : null,
            'authentication' => !empty($this->getAuthentication()) ? $this->getAuthentication()->toArray() : null,
            'date' => $this->getDate()->format(Date::JAVASCRIPT_ISO_FORMAT),
            'gender' => $this->getGender()->value(),
            'billingAddress' => !empty($this->getBillingAddress()) ? $this->getBillingAddress()->toArray() : null,
            'shippingAddresses' => $shippingAddresses,
            'coins' => $this->getCoins()
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
        try {

            $gender = Enum\Gender::memberByValue($array['gender']);

        } catch (\Exception $exception) {

            $gender = null;
        }

        $shippingAddresses = [];
        foreach ($array['shippingAddresses'] as $shippingAddress) {
            $shippingAddresses[] = Element\User\Address::fromArray((array) $shippingAddress);
        }

        return (new static($array['_id']))
            ->setName($array['name'])
            ->setEmail($array['email'])
            ->setPhone($array['phone'])
            ->setMobile($array['mobile'])
            ->setPassword($array['password'])
            ->setDocumentNumber($array['documentNumber'])
            ->setDob($array['dob'])
            ->setPersonalDocument($array['personalDocument'])
            ->setInvitedBy(!empty((array) $array['invitedBy']) ? Element\User\Standard::fromArray((array) $array['invitedBy']) : null)
            ->setAuthentication(!empty((array) $array['authentication']) ? Element\User\Authentication::fromArray((array) $array['authentication']) : null)
            ->setDate(new \DateTime($array['date']))
            ->setGender($gender)
            ->setBillingAddress(!empty((array) $array['billingAddress']) ? Element\User\Address::fromArray((array) $array['billingAddress']) : null)
            ->setShippingAddresses($shippingAddresses)
            ->setCoins($array['coins']);
    }
}

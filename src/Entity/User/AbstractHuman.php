<?php

namespace App\Entity\User;

use App\Entity\AbstractFoundation;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractHuman.
 *
 * @package    App\Entity\User
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
abstract class AbstractHuman extends AbstractFoundation
{
    const MARITAL_STATUS_SINGLE    = 3;
    const MARITAL_STATUS_MARIED    = 2;
    const MARITAL_STATUS_SEPARATED = 1;
    const GENDER_MAN               = 1;
    const GENDER_WOMAN             = 2;
    /**
     * @var string|null
     *
     * @ORM\Column(type="string",name="lastname", length=250, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     * @Assert\Length(max=250)
     * @Groups("item:read")
     * @Groups("item:list")
     */
    protected $lastname;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string",name="firstname", length=250, nullable=true)
     * @Assert\Length(max=250)
     * @Groups("item:read")
     * @Groups("item:list")
     */
    protected $firstname;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string",name="birth_place", length=250, nullable=true)
     * @Assert\Length(max=250)
     * @Groups("item:read")
     */
    protected $birthPlace;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="birth_date", type="datetime", nullable=true)
     * @Assert\Type(type="object")
     */
    protected $birthDate;

    /**
     * @var integer|null
     *
     * @ORM\Column(name="gender", type="integer", nullable=true)
     * @Groups("item:read")
     */
    protected $gender = 1;

    /**
     * @return string|null
     * @Groups("item:read")
     * @Groups("item:list")
     */
    public function getFullname()
    {
        return $this->getLastname() . ' ' . $this->getFirstname();
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return (string)$this->lastname;
    }

    /**
     * @param string|null $lastname
     * @return AbstractHuman
     */
    public function setLastname(?string $lastname): AbstractHuman
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname()
    {
        return (string)$this->firstname;
    }

    /**
     * @param string|null $firstname
     * @return AbstractHuman
     */
    public function setFirstname(?string $firstname): AbstractHuman
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBirthPlace(): ?string
    {
        return $this->birthPlace;
    }

    /**
     * @param string|null $birthPlace
     * @return AbstractHuman
     */
    public function setBirthPlace(?string $birthPlace): AbstractHuman
    {
        $this->birthPlace = $birthPlace;
        return $this;
    }

    /**
     * @return string
     * @Groups("item:read")
     */
    public function getBirthDateValue()
    {
        if ($this->getBirthDate())
            return $this->getBirthDate()->format('m/d/Y');
        return 'N/A';
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime|null $birthDate
     * @return AbstractHuman
     */
    public function setBirthDate(?DateTime $birthDate): AbstractHuman
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMan(): bool
    {
        return $this->getGender() == self::GENDER_MAN;
    }

    /**
     * @return int|null
     */
    public function getGender(): ?int
    {
        return $this->gender;
    }

    /**
     * @param int|null $gender
     * @return AbstractHuman
     */
    public function setGender(?int $gender): AbstractHuman
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Permet de récupérer les valeur d'un objet pour un autre.
     * @param $content
     */
    public function parse($content)
    {
        if (isset($content->firstname))
            $this->setFirstname($content->firstname);
        if (isset($content->lastname))
            $this->setLastname($content->lastname);
        if (isset($content->gender))
            $this->setGender($content->gender);
        if (isset($content->birthDate))
            $this->setBirthDate($content->birthDate);
        if (isset($content->birthPlace))
            $this->setBirthPlace($content->birthPlace);
    }
}

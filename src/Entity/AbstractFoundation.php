<?php

namespace App\Entity;

use App\Contracts\FoundationInterface;
use App\Entity\User\Actor;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * AbstractFoundation Main wrap entity
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @subpackage App\Entity
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractFoundation implements FoundationInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups("item:read")
     * @Groups("item:list")
     */
    protected $id;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     *
     */
    protected $dateAdd;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_upd", type="datetime", nullable=true)
     */
    protected $dateUpd;
    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=true, options={"default" : 0})
     * @Groups("item:read")
     */
    protected $deleted = false;

    /**
     * @var bool $locked permet de savoir si l'entité peut encore subir des modification
     *
     * @ORM\Column(name="locked", type="boolean", nullable=true, options={"default" : 0})
     */
    protected $locked = false;
    /**
     * @var bool
     *
     * @ORM\Column(name="validated", type="boolean")
     * @Groups("item:read")
     */
    protected $validated = true;
    /**
     * @var mixed $i18n champ in current locale if entity is multilanguage
     */
    protected $i18n;

    /**
     * @var AbstractFoundationI18n[] $i18n entity in all availables languages
     */
    protected $i18ns;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\User\Actor", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $author;

    /**
     * AbstractFoundation constructor.
     */
    public function __construct($locales = [])
    {
        $this->dateAdd   = new Datetime();
        $this->validated = true;
        $this->i18ns     = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateDate()
    {
        $this->setDateUpd(new Datetime());
    }

    /**
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param boolean $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * @return bool
     */
    public function isValidated()
    {
        return $this->validated;
    }

    /**
     * @param bool $validated
     *
     * @return AbstractFoundation
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;
        return $this;
    }

    /**
     * Get author
     *
     * @return \App\Entity\User\Actor
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author
     *
     * @param \App\Entity\User\Actor $author
     *
     * @return AbstractFoundation
     */
    public function setAuthor(Actor $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Remove i18ns
     *
     * @param \App\Entity\AbstractFoundationI18n $i18n
     */
    public function removeI18n($i18n)
    {
        if (!empty($this->i18ns))
            $this->i18ns->removeElement($i18n);
    }

    /**
     * Get i18ns
     *
     * @return AbstractFoundationI18n[]
     */
    public function getI18ns()
    {
        return $this->i18ns;
    }

    /**
     * @param $itemsI18n
     */
    public function setI18ns($itemsI18n)
    {
        $this->i18ns->clear();
        foreach ($itemsI18n as $item) {
            $this->addI18n($item);
        }
    }

    public function addI18n($i18n)
    {/*Implements this for add translation*/
    }

    /**
     * @return bool
     */
    public function isNotLocked(): bool
    {
        return !($this->isLocked());
    }

    //Defines all translations items

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * @param bool $locked
     * @return AbstractFoundation
     */
    public function setLocked(bool $locked): AbstractFoundation
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * Ce permet d'être toujours présent dans le résultat d'une api.
     * Cela permet l'autocomplétion dans une liste déroulante
     *
     * @return string
     * @Groups("item:read")
     * @Groups("item:list")
     */
    abstract function getLabel();

    /**
     * Enable de get entity in one language.
     *
     * @param string $locale
     *
     * @return AbstractFoundationI18n
     */
    public function getI18n($locale = 'en')
    {
        if (!empty($this->i18ns[$locale]))
            $this->i18n = $this->i18ns[$locale];
        else
            $this->i18n = $this->getOneI18n();
        return $this->i18n;
    }

    /**
     * @return \App\Entity\AbstractFoundationI18n|mixed
     */
    public function getOneI18n()
    {
        if ($this->i18ns) {
            foreach ($this->i18ns as $item) {
                $this->i18n = $item;
                break;
            }
        }
        return $this->i18n;
    }

    /**
     * @param $val
     * @return bool
     */
    public function is_decimal($val)
    {
        return is_numeric($val) && floor($val) != $val;
    }

    /**
     * @return \App\Entity\AbstractFoundation
     */
    public function lockIt(): AbstractFoundation
    {
        $this->locked = true;
        return $this;
    }

    /**
     * @return \App\Entity\AbstractFoundation
     */
    public function unlockedIt(): AbstractFoundation
    {
        $this->locked = false;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNotLock()
    {
        return $this->locked == false;
    }

    /**
     * @return bool
     */
    public function isLock()
    {
        return $this->locked == true;
    }

    /**
     * @return string
     * @Groups("item:read")
     * @Groups("item:list")
     */
    public function getDateAddValue()
    {
        if ($this->getDateAdd())
            return $this->getDateAdd()->format('c');
        return date('c');
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return $this
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * @return string
     * @Groups("item:read")
     * @Groups("item:list")
     */
    public function getDateUpdValue()
    {
        if ($this->getDateUpd())
            return $this->getDateUpd()->format('m/d/Y');
        return date('m/d/Y');
    }

    /**
     * Get dateUpd
     *
     * @return \DateTime
     */
    public function getDateUpd()
    {
        return $this->dateUpd;
    }

    /**
     * Set dateUpd
     *
     * @param \DateTime $dateUpd
     *
     * @return $this
     */
    public function setDateUpd($dateUpd)
    {
        $this->dateUpd = $dateUpd;

        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Permet de cloner un objet
     */
    public function __clone()
    {
        $this->id = 0;
    }
}

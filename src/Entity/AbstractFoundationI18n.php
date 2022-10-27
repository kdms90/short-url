<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AbstractFoundation Main wrap entity for translation
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @subpackage App\Entity
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 * @ORM\MappedSuperclass
 */
abstract class AbstractFoundationI18n
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=5)
     * @Assert\Length(max="5")
     * @Groups("item:read")
     * @Groups("item:list")
     */
    protected $locale;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text")
     * @Assert\NotBlank(message = "Le champ nom ne peut etre nul")
     * @Assert\Length(min=3)
     * @Groups("item:read")
     * @Groups("item:list")
     */
    protected $name;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups("item:read")
     * @Groups("item:list")
     */
    protected $description;

    protected $tags;
    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=190, nullable=true)
     * @Assert\Length(max="190")
     * @Groups("item:read")
     * @Groups("item:list")
     */
    protected $slug;

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
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set locale
     *
     * @param string $locale
     *
     * @return AbstractFoundationI18n
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AbstractFoundationI18n
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return ucfirst($this->name);
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AbstractFoundationI18n
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param string $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
}

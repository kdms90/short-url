<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractRole
 * @package App\Entity
 */
abstract class AbstractRole extends AbstractFoundation
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text")
     * @Assert\NotBlank(message = "Le champ nom ne peut etre nul")
     * @Assert\Length(min=3)
     * @Groups("item:read")
     */
    protected $name;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Groups("item:read")
     */
    protected $description;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=true)
     * @Groups("item:read")
     */
    protected $isDefault = false;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", nullable=true)
     * @Groups("item:read")
     */
    protected $code;
    /**
     * @var AbstractAuthorization[]|\Doctrine\Common\Collections\ArrayCollection
     */
    protected $authorizations;

    /**
     * @return bool|null
     */
    public function isDefault(): ?bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool|null $isDefault
     * @return AbstractRole
     */
    public function setIsDefault(?bool $isDefault): AbstractRole
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return AbstractRole
     */
    public function setCode(?string $code): AbstractRole
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return AbstractRole
     */
    public function setDescription(string $description): AbstractRole
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Add authorization
     *
     * @param \App\Entity\AbstractAuthorization $authorization
     * @return $this
     */
    public function addAuthorization(AbstractAuthorization $authorization)
    {
        $this->authorizations[] = $authorization;

        return $this;
    }

    /**
     * Remove authorization
     *
     * @param \App\Entity\AbstractAuthorization $authorization
     */
    public function removeAuthorization(AbstractAuthorization $authorization)
    {
        $this->authorizations->removeElement($authorization);
    }

    /**
     * Get authorizations key
     *
     * @return array
     */
    public function getAuthorizationsActions()
    {
        $actions = [];
        foreach ($this->getAuthorizations() as $authorization)
            if (!in_array($authorization->getKey(), $actions))
                $actions[] = $authorization->getKey();

        return array_unique($actions);
    }

    /**
     * Get authorizations
     *
     * @return \App\Entity\AbstractAuthorization[]
     */
    public function getAuthorizations()
    {
        return $this->authorizations;
    }

    /**
     * Permet de parser les valeurs envoyées par l'API
     *
     * @param $content
     */
    public function parse($content)
    {
        $this->setName($content->name);
        $this->setCode($content->code);
        $this->setDescription($content->description);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * Ce permet d'être toujours présent dans le résultat d'une api.
     * Cela permet l'autocomplétion dans une liste déroulante
     *
     * @return string
     * @Groups("item:read")
     */
    public function getLabel()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return AbstractRole
     */
    public function setName(string $name): AbstractRole
    {
        $this->name = $name;
        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractAuthorization
 * @package App\Entity
 */
abstract class AbstractAuthorization extends AbstractFoundation
{
    /**
     * @var AbstractRole
     */
    protected $role;
    /**
     * @var string
     *
     * @ORM\Column(name="key_rule", type="string", length=190)
     * @Assert\NotBlank(message = "Veuillez indiquer la clé du droit")
     * @Assert\Length(min=3, max="190")
     * @Groups("item:read")
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(name="key_group", type="string", length=190)
     * @Assert\NotBlank(message = "Veuillez indiquer la clé du droit de la soumission")
     * @Assert\Length(min=3, max="190")
     * @Groups("item:read")
     */
    protected $keyGroup;

    /**
     * ActorRoleAuthorization constructor.
     * @param \App\Entity\AbstractRole $role
     * @param null $key
     * @param null $keyGroup
     */
    public function __construct(AbstractRole $role = null, $key = null, $keyGroup = null)
    {
        if ($role)
            $this->setRole($role);
        if ($key)
            $this->setKey($key);
        if ($keyGroup)
            $this->setKeyGroup($keyGroup);
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getKeyGroup()
    {
        return $this->keyGroup;
    }

    /**
     * @param string $keyGroup
     * @return AbstractAuthorization
     */
    public function setKeyGroup($keyGroup)
    {
        $this->keyGroup = $keyGroup;
        return $this;
    }

    /**
     * @return \App\Entity\AbstractRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param \App\Entity\AbstractRole $role
     * @return AbstractAuthorization
     */
    public function setRole(AbstractRole $role)
    {
        $this->role = $role;
        return $this;
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
        return $this->getKey();
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return AbstractAuthorization
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
}

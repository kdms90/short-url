<?php

namespace App\Entity\User;

use App\Entity\AbstractFoundation;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CurrentWorkspace
 * @package App\Entity\User
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @ORM\Table(name="app_user_current_workspace")
 * @ORM\Entity(repositoryClass="App\Repository\User\CurrentWorkspaceRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"actor"},
 *     message="email.exists"
 * )
 */
class CurrentWorkspace extends AbstractFoundation
{
    /** @var string Renseigne sur le type de compte utilisé */
    const GUEST         = 'guest';
    const ADMINISTRATOR = 'administrator';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Actor", cascade={"persist"},fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * @Groups("item:read")
     */
    private $actor;
    /**
     * @var string
     *
     * @ORM\Column(name="context_type", type="string", length=190)
     * @Assert\NotBlank(message = "Vous devez indiquer le type de context")
     * @Assert\Length(min=3)
     * @Groups("item:read")
     */
    private $contextType;

    /**
     * Return next sequence of entity code
     *
     * @return string
     */
    public static function getSequence()
    {
        return 'app.entity.user.current_workspace';
    }

    /**
     * Get user
     *
     * @return \App\Entity\User\Actor
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Set actor
     *
     * @param \App\Entity\User\Actor $actor
     *
     * @return CurrentWorkspace
     */
    public function setActor(Actor $actor)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get contextType
     *
     * @return string
     */
    public function getContextType()
    {
        return $this->contextType;
    }

    /**
     * Set contextType
     *
     * @param string $contextType
     *
     * @return CurrentWorkspace
     */
    public function setContextType($contextType)
    {
        $this->contextType = $contextType;

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
        return $this->getCompany()->getLabel();
    }
}

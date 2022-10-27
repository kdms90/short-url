<?php

namespace App\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Actor AccountManager
 *
 * Cette classe permet de manager les attributs du propriétaire principal d'un compte.
 *
 * @package    App\Entity\User
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @ORM\Table(name="app_user_actor")
 * @ORM\Entity(repositoryClass="App\Repository\User\ActorRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Actor extends AbstractHuman
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_connection_date", type="datetime", nullable=true)
     */
    protected $lastConnectionDate;
    /**
     * @var string
     *
     * @ORM\Column(type="string",name="matricule", length=190, nullable=true)
     * @Assert\Length(max=190)
     */
    private $matricule;
    /**
     * @var \App\Entity\User\Access
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User\Access", inversedBy="actor", cascade={"persist"},fetch="EAGER")
     * @ORM\JoinColumn(nullable=true, onDelete="cascade")
     * @Assert\Valid()
     */
    private $access;
    /**
     * @var \App\Entity\User\ActorMember[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\User\ActorMember", mappedBy="member",
     *      cascade={"persist","remove"}, fetch="LAZY")
     */
    private $actorRoles;

    /**
     * Actor constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->locationRoles   = new ArrayCollection();
        $this->actorRoles      = new ArrayCollection();
        $this->evaluationRoles = new ArrayCollection();
    }

    /**
     * Return next sequence of entity code
     *
     * @return string
     */
    public static function getSequence()
    {
        return 'app.entity.user.actor';
    }

    /**
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule . "";
    }

    /**
     * @param string $matricule
     *
     * @return Actor
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;
        return $this;
    }

    /**
     * @return string|null
     * @Groups("item:read")
     * @Groups("item:list")
     */
    public function getLastConnectionDateValue()
    {
        if ($this->getDateAdd() && $this->getLastConnectionDate())
            return $this->getLastConnectionDate()->format('c');
        return null;
    }

    /**
     * Get $lastConnectionDate
     *
     * @return \DateTime
     */
    public function getLastConnectionDate()
    {
        return $this->lastConnectionDate;
    }

    /**
     * Set $lastConnectionDate
     *
     * @param \DateTime $lastConnectionDate
     *
     * @return $this
     */
    public function setLastConnectionDate($lastConnectionDate)
    {
        $this->lastConnectionDate = $lastConnectionDate;

        return $this;
    }

    /**
     * Remove actorRole
     *
     * @param \App\Entity\User\ActorMember $actorRole
     */
    public function removeActorRole(ActorMember $actorRole)
    {
        $this->actorRoles->removeElement($actorRole);
    }

    /**
     * Get actorRoles
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\User\ActorMember[]
     */
    public function getActorRoles()
    {
        return $this->actorRoles;
    }

    /**
     * @param ArrayCollection $items
     *
     * @return $this
     */
    public function setActorRoles(ArrayCollection $items)
    {
        $this->actorRoles->clear();
        foreach ($items as $item)
            $this->addActorRole($item);
        return $this;
    }

    /**
     * Add actorRole
     *
     * @param \App\Entity\User\ActorMember $actorRole
     *
     * @return $this
     */
    public function addActorRole(ActorMember $actorRole)
    {
        $actorRole->setMember($this);
        $this->actorRoles[] = $actorRole;

        return $this;
    }

    /**
     * Email de connexion
     *
     * @return string
     * @Groups("item:read")
     * @Groups("item:list")
     */
    public function getEmail()
    {
        if ($this->getAccess())
            return $this->getAccess()->getEmail();
        return '';
    }

    /**
     * @return \App\Entity\User\Access
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param \App\Entity\User\Access $access
     *
     * @return Actor
     */
    public function setAccess(Access $access = null)
    {
        $this->access = $access;
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
    public function getLabel()
    {
        return $this->getFullname();
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }
}

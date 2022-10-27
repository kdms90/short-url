<?php

namespace App\Entity\User;

use App\Contracts\RoleMemberInterface;
use App\Entity\AbstractFoundation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class ActorMember
 * @package App\Entity\User
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @ORM\Table(name="app_user_actor_member")
 * @ORM\Entity(repositoryClass="App\Repository\User\ActorMemberRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ActorMember extends AbstractFoundation implements RoleMemberInterface
{
    /**
     * @var \App\Entity\User\Actor
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Actor", cascade={"persist"},fetch="EAGER")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $author;

    /**
     * @var \App\Entity\User\ActorRole[]
     * @ORM\ManyToMany(targetEntity="App\Entity\User\ActorRole", inversedBy="members", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="app_user_actor_member_role",
     *      joinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Actor", inversedBy="actorRoles", cascade={"persist"},fetch="LAZY")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * ActorMember constructor.
     * @param array $locales
     */
    public function __construct($locales = [])
    {
        parent::__construct($locales);
        $this->roles = new ArrayCollection();
    }

    /**
     * Add role
     *
     * @param \App\Entity\User\ActorRole $role
     *
     * @return ActorMember
     */
    public function addRole(ActorRole $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \App\Entity\User\ActorRole $role
     */
    public function removeRole(ActorRole $role)
    {
        $this->roles->removeElement($role);
    }    /**
     * Get member
     *
     * @return \App\Entity\User\Actor
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Get roles
     *
     * @return \App\Entity\User\ActorRole[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set member
     *
     * @param \App\Entity\User\Actor $member
     *
     * @return ActorMember
     */
    public function setMember(Actor $member)
    {
        $this->member = $member;

        return $this;
    }




    /**
     * Set author
     *
     * @param \App\Entity\User\Actor $author
     *
     * @return $this
     */
    public function setAuthor(Actor $author = null)
    {
        $this->author = $author;

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
     * Ce permet d'être toujours présent dans le résultat d'une api.
     * Cela permet l'autocomplétion dans une liste déroulante
     *
     * @return string
     * @Groups("item:read")
     */
    public function getLabel()
    {
        return $this->getMember()->getFullname();
    }

    /**
     * Return next sequence of entity code
     *
     * @return string
     */
    public static function getSequence()
    {
        return 'app.actor.member';
    }
}


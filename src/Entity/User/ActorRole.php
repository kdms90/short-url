<?php

namespace App\Entity\User;

use App\Entity\AbstractRole;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ActorRole
 * @package App\Entity\User
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @ORM\Table(name="app_user_actor_role")
 * @ORM\Entity(repositoryClass="App\Repository\User\ActorRoleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ActorRole extends AbstractRole
{
    /**
     * @var \App\Entity\User\ActorRoleAuthorization[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\User\ActorRoleAuthorization", mappedBy="role",fetch="EXTRA_LAZY")
     */
    protected $authorizations;
    /**
     * @var \App\Entity\User\ActorMember
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User\ActorMember", mappedBy="roles",fetch="EXTRA_LAZY")
     */
    private $members;

    /**
     * ActorRole constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->members = new ArrayCollection();
    }

    /**
     * Return next sequence of entity code
     *
     * @return string
     */
    public static function getSequence()
    {
        return 'app.user.actor.role';
    }

    /**
     * Add member
     *
     * @param \App\Entity\User\ActorMember $member
     *
     * @return ActorRole
     */
    public function addMember(ActorMember $member)
    {
        $this->members[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param \App\Entity\User\ActorMember $member
     */
    public function removeMember(ActorMember $member)
    {
        $this->members->removeElement($member);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection | \App\Entity\User\ActorMember[]
     */
    public function getMembers()
    {
//        $criteria = Criteria::create()
//            ->where(Criteria::expr()->eq('deleted', 1))
//            ->orderBy(['endDate' => 'DESC'])
//            ->setMaxResults(20);
        return $this->members;
    }
}

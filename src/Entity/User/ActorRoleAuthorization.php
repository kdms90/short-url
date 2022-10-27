<?php

namespace App\Entity\User;

use App\Entity\AbstractAuthorization;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ActorRoleAuthorization
 * @package App\Entity\User
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @ORM\Table(name="app_user_actor_role_authorization")
 * @ORM\Entity(repositoryClass="App\Repository\User\ActorRoleAuthorizationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ActorRoleAuthorization extends AbstractAuthorization
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\ActorRole", inversedBy="authorizations",
     *     cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * @Groups("item:read")
     */
    protected $role;

    /**
     * Return next sequence of entity code
     *
     * @return string
     */
    public static function getSequence()
    {
        return 'app.actor.role.authorization';
    }
}


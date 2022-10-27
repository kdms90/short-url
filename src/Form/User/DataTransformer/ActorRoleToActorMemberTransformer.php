<?php

namespace App\Form\User\DataTransformer;

use App\Entity\User\ActorMember;
use App\Entity\User\ActorRole;
use App\Form\DataTransformer\AbstractRoleToMemberTransformer;

/**
 * Class ActorRoleToActorMemberTransformer
 * @package DB\UserBundle\Form\DataTransformer
 */
class ActorRoleToActorMemberTransformer extends AbstractRoleToMemberTransformer
{
    protected $roleClass   = ActorRole::class;
    protected $memberClass = ActorMember::class;
}
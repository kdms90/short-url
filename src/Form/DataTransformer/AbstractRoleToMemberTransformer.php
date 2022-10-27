<?php

namespace App\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class RoleToMemberTransformer
 * @package App\Form\Accounting\DataTransformer
 */
abstract class AbstractRoleToMemberTransformer implements DataTransformerInterface
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    protected $entityManager;
    /** @var int */
    protected $actor;
    /** @var int */
    protected $current_actor;
    /** @var int */
    protected $company;
    /** @var string $roleClass namespace vers la classe contenant les roles */
    protected $roleClass;
    /** @var string $memberClass namespace de la classe contenant les membre ayant un role */
    protected $memberClass;

    /**
     * RoleToMemberTransformer constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param      $current_actor_id
     * @param      $actor_id
     *
     * @param int $company_id
     * @return $this
     */
    public function setDependency($current_actor_id, $actor_id, $company_id = 0)
    {
        $this->current_actor = $current_actor_id;
        $this->actor         = $actor_id;
        $this->company       = $company_id;
        return $this;
    }

    /**
     * Transforms a AccountingMember to an array of AccountingRole objects.
     *
     * @param array $members
     *
     * @return ArrayCollection
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function transform($members)
    {
        $roles = new ArrayCollection();
        // no issue number? It's optional, so that's ok
        if (!$members) {
            return $roles;
        }
        foreach ($members as $member) {
            foreach ($member->getRoles() as $role)
                $roles->add($role);
        }
        return $roles;
    }

    /**
     * Data from form
     *
     * Transforms an AccountingRole to AccountingMember.
     *
     * @param ArrayCollection $roles
     *
     * @return ArrayCollection
     */
    public function reverseTransform($roles)
    {
        if (null === $roles) {
            return new ArrayCollection();
        }
        $members = new ArrayCollection();
        foreach ($roles as $role) {
            $accessExists = $this->entityManager->getRepository($this->roleClass)->checkIfUserAccessExists($this->actor, $role->getId());
            if (!$accessExists) {
                $member = new $this->memberClass();
                $member->setAuthor($this->getCurrentUser());
                $member->addRole($role);
                $members->add($member);
            }
        }
        $rolesIds = [];
        //Delete old roles
        $oldsAccess = $this->entityManager->getRepository($this->roleClass)->getUserAccessOfCompany($this->actor, $this->company);
        foreach ($roles as $role) {
            $rolesIds[] = $role->getId();
        }
        $oldsAccessIds    = array_map(function ($obj) {
            return $obj->getId();
        }, $oldsAccess);
        $rolesIdsToRemove = array_diff($oldsAccessIds, $rolesIds);
        foreach ($rolesIdsToRemove as $role_id) {
            $accountingMembers = $this->entityManager->getRepository($this->memberClass)->getUserOfAccess($this->actor, $role_id);
            foreach ($accountingMembers as $toDelete) {
                $this->entityManager->remove($toDelete);
            }
        }
        return $members;
    }

    /**
     * @return \App\Entity\User\Actor|null|object
     */
    protected function getCurrentUser()
    {
        return $this->entityManager->getRepository('App\Entity\User\Actor')->find($this->current_actor);
    }
}
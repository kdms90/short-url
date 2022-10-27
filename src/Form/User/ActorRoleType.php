<?php

namespace App\Form\User;

use App\Form\FoundationRoleType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ActorRoleType
 * @package App\Form\User
 */
class ActorRoleType extends FoundationRoleType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => 'App\Entity\User\ActorRole',
            'oldRoles'          => [],
            'additionalRules'   => [],
            'oldAuthorizations' => [],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_user_actorrole';
    }
}

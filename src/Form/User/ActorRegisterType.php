<?php

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ActorRegisterType
 * @package App\Form\User
 */
class ActorRegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, [
                'required'           => true,
                'label'              => 'user.lastname',
                'translation_domain' => 'app',
                'attr'               => [
                    'placeholder' => 'user.lastname',
                ],
                'constraints'        => [
                    new NotBlank(['message' => 'Veuillez renseigner votre nom.']),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label'              => 'user.surname',
                'translation_domain' => 'app',
                'attr'               => [
                    'placeholder' => 'user.surname',
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'           => 'App\Entity\User\Actor',
            'locale'               => 'en',
            'roles'                => [],
            'actor_id'             => 0,
            'employee_id'          => 0,
            'enabledRoles'         => true,
            'isFront'              => false,
            'show_consultant_attr' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_user_actor';
    }
}

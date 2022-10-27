<?php
namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class AccessType
 * @package App\Form\User
 */
class AccessType extends AbstractType
{
    private $isFront = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['isFront']))
            $this->isFront = (bool)$options['isFront'];
        $builder
            ->add('email', EmailType::class, [
                'label'              => 'form.email',
                'translation_domain' => 'security',
                'attr'               => [
                    'placeholder'  => 'form.email',
                    'autocomplete' => 'off',
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'           => PasswordType::class,
                'options'        => [
                    'translation_domain' => 'security',
                    'attr'               => [
                        'autocomplete' => 'off',
                    ],
                ],
                'first_options'  => ['label' => false, 'attr' => ['placeholder' => 'form.password']],
                'second_options' => ['label' => false, 'attr' => ['placeholder' => 'form.password_confirmation']],
                'first_name'     => 'mainField',
                'second_name'    => 'repeatField',
            ]);
        if ($this->isFront) {
            $builder
                ->add('actor', ActorRegisterType::class, [
                    'label'       => false,
                    'constraints' => [new NotNull()],
                ])
                ->add('submit', SubmitType::class, ['label' => 'Créer mon compte', 'attr' => ['class' => 'btn-primary btn-block']]);
        } else {
            $builder
                ->add('isActive', CheckboxType::class, [
                        'label'              => 'enabled_user',
                        'translation_domain' => 'core',
                        'attr'               => ['class' => 'js-switch'],
                        'required'           => false,
                    ]
                );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'App\Entity\User\Access',
            'isFront'            => false,
            'isEditPersonalInfo' => false,
        ]);
    }
}

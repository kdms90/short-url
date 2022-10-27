<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FoundationRoleType
 * @package App\Form\Foundation
 */
class FoundationRoleType extends AbstractType
{
    private $oldRoles          = [];
    private $additionalRules   = [];
    private $oldAuthorizations = [];

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['oldRoles']))
            $this->oldRoles = $options['oldRoles'];
        if (isset($options['additionalRules']))
            $this->additionalRules = $options['additionalRules'];
        if (isset($options['oldAuthorizations']))
            $this->oldAuthorizations = $options['oldAuthorizations'];
        $builder
            ->add('name', TextareaType::class, [
                    'translation_domain' => 'external',
                    'required'           => true,
                    'label'              => false,
                    'attr'               => [
                        'class'       => 'textarea-title text-type',
                        'placeholder' => 'role_name',
                    ],
                ]

            )
            ->add('description', TextareaType::class, [
                    'label'    => 'Description',
                    'required' => true,
                    'attr'     => ['class' => 'editor'],
                ]

            );
        foreach ($this->oldRoles as $keyGroup => $value) {
            $label = 'rules.' . $keyGroup;
            $builder->add($keyGroup, ChoiceType::class, [
                    'choices'            => $value,
                    'label'              => $label,
                    'data'               => !empty($this->oldAuthorizations[$keyGroup]) ? $this->oldAuthorizations[$keyGroup] : [],
                    'translation_domain' => 'form',
                    'expanded'           => true,
                    'multiple'           => true,
                    'mapped'             => false,
                    'choice_label'       => false,
                    'attr'               => ['class' => 'native-check'],
                ]

            );
        }
        foreach ($this->additionalRules as $keyGroup => $values) {
            $label = 'rules.' . $keyGroup;
            $builder->add($keyGroup, ChoiceType::class, [
                    'choices'            => $values,
                    'label'              => $label,
                    'data'               => !empty($this->oldAuthorizations[$keyGroup]) ? $this->oldAuthorizations[$keyGroup] : [],
                    'translation_domain' => 'form',
                    'expanded'           => true,
                    'multiple'           => true,
                    'mapped'             => false,
                    'attr'               => ['class' => 'native-check'],
                ]

            );
        }
        parent::buildForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'        => 'App\Entity\FoundationRole',
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
        return 'app_foundation_foundationrole';
    }
}

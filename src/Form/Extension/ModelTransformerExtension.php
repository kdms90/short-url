<?php

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ModelTransformerExtension
 * @package App\Form\Extension
 */
class ModelTransformerExtension extends AbstractTypeExtension
{
    protected $custom = 1;

    public function getExtendedType()
    {
        return FormType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if (isset($options['model_transformer'])) {
            $builder->addModelTransformer($options['model_transformer']);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(
            [
                'model_transformer' => null,
                'locale'            => 'fr',
                'actorId'           => 0,
                'current_actor_id'  => 0,
                'company_id'        => 0,
                'entity_id'         => 0,
                'isAdministrator'   => false,
            ]
        );
    }

    /**
     * @return iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
<?php
namespace App\Form\Business;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class AccessType
 * @package App\Form\User
 */
class UrlType extends AbstractType
{
    private $isFront = false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['isFront']))
            $this->isFront = (bool)$options['isFront'];
        $builder
            ->add('original', \Symfony\Component\Form\Extension\Core\Type\UrlType::class, [
                'label'              => 'Lien',
                'translation_domain' => 'security',
                'attr'               => [
                    'placeholder'  => 'Copiez-coller l\'adresse à minimiser pour générer un lien raccourci à partager à vos communautés.',
                    'autocomplete' => 'off',
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Raccourcir', 'attr' => [
                'class' => 'btn-primary btn-block'
            ]]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'App\Entity\Business\Url',
            'isFront'            => false,
            'isEditPersonalInfo' => false,
        ]);
    }
}

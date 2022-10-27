<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class AbstractFoundationI18nType
 * @package App\Form
 */
abstract class AbstractFoundationI18nType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->get('i18ns')->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                /** @var \App\Entity\AbstractFoundationI18n[] $i18nsTmp */
                $i18nsTmp    = $form->getData();
                $i18ns       = [];
                $name        = '';
                $description = '';
                foreach ($i18nsTmp as $i18n) {
                    if (trim(strlen($i18n->getName())) > 2)
                        $name = $i18n->getName();
                    if (trim(strlen($i18n->getDescription())))
                        $description = $i18n->getDescription();
                    if (!empty($name))
                        break;
                }
                foreach ($i18nsTmp as $i18n) {
                    if (empty($i18n->getName())) {
                        $i18n->setName($name);
                    }
                    if (empty($i18n->getDescription())) {
                        $i18n->setDescription($description);
                    }
                    $i18ns[] = $i18n;
                }
                //$form->setData($i18ns);
            }
        );
    }
}

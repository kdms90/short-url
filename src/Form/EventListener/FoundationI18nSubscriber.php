<?php

namespace App\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PropertyAccess\PropertyAccess;

/***
 * Enable to load translations in other lang if not exists
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    DB\CoreBundle
 * @subpackage App\Controller
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class FoundationI18nSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $_data              = $accessor->getValue($data, $this->propertyPathToProductType);
        $productType        = ($_data) ? $_data : null;
        $productCategory_id = ($productType) ? $productType->getProductCategory()->getId() : null;

        $this->addProductTypeForm($form, $productCategory_id, $productType);
    }

    public function onSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $productCategory_id = array_key_exists('productCategory', $data) ? $data['productCategory'] : null;

        $this->addProductTypeForm($form, $productCategory_id);
    }
} 
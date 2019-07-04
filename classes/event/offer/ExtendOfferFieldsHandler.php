<?php namespace Lovata\FacebookShopaholic\Classes\Event\Offer;

use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;

use Lovata\Shopaholic\Models\Offer;
use Lovata\Shopaholic\Controllers\Offers;

/**
 * Class ExtendOfferFieldsHandler
 *
 * @package Lovata\FacebookShopaholic\Classes\Event\Offer
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendOfferFieldsHandler extends AbstractBackendFieldHandler
{
    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Offer::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Offers::class;
    }

    /**
     * Extend fields model
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        $this->addField($obWidget);
    }

    /**
     * Remove fields model
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function addField($obWidget)
    {
        $obWidget->addTabFields(
            [
                'section_facebook' => [
                    'label' => 'lovata.facebookshopaholic::lang.field.section_facebook',
                    'type'  => 'section',
                    'span'  => 'full',
                    'tab'   => 'lovata.toolbox::lang.tab.images',
                ],
                'preview_image_facebook'  => [
                    'label'     => 'lovata.toolbox::lang.field.preview_image',
                    'type'      => 'fileupload',
                    'span'      => 'full',
                    'required'  => true,
                    'mode'      => 'image',
                    'tab'       => 'lovata.toolbox::lang.tab.images',
                    'fileTypes' => 'jpeg,png',
                ],
                'images_facebook'         => [
                    'label'     => 'lovata.toolbox::lang.field.images',
                    'type'      => 'fileupload',
                    'span'      => 'full',
                    'required'  => false,
                    'mode'      => 'image',
                    'tab'       => 'lovata.toolbox::lang.tab.images',
                    'fileTypes' => 'jpeg,png',
                ],
            ]
        );
    }
}

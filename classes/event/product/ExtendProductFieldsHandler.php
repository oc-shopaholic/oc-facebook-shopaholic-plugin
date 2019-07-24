<?php namespace Lovata\FacebookShopaholic\Classes\Event\Product;

use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Controllers\Products;
use Lovata\FacebookShopaholic\Models\FacebookSettings;

/**
 * Class ExtendProductFieldsHandler
 *
 * @package Lovata\FacebookShopaholic\Classes\Event\Product
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendProductFieldsHandler extends AbstractBackendFieldHandler
{
    /**
     * Extend fields model
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendFields($obWidget)
    {
        $sCodeModelForImages = FacebookSettings::getValue('code_model_for_images', '');
        if ($sCodeModelForImages != FacebookSettings::CODE_PRODUCT) {
            return;
        }

        $arFields = [
            'section_facebook' => [
                'label' => 'lovata.facebookshopaholic::lang.field.section_facebook',
                'tab'   => 'lovata.toolbox::lang.tab.images',
                'type'  => 'section',
                'span'  => 'full',
            ],
            'preview_image_facebook' => [
                'label'     => 'lovata.toolbox::lang.field.preview_image',
                'tab'       => 'lovata.toolbox::lang.tab.images',
                'type'      => 'fileupload',
                'span'      => 'left',
                'required'  => true,
                'mode'      => 'image',
                'fileTypes' => 'jpeg,png',
            ],
            'images_facebook' => [
                'label'     => 'lovata.toolbox::lang.field.images',
                'type'      => 'fileupload',
                'span'      => 'left',
                'required'  => false,
                'mode'      => 'image',
                'tab'       => 'lovata.toolbox::lang.tab.images',
                'fileTypes' => 'jpeg,png',
            ],
        ];

        $obWidget->addTabFields($arFields);
    }

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass() : string
    {
        return Product::class;
    }

    /**
     * Get controller class name
     * @return string
     */
    protected function getControllerClass() : string
    {
        return Products::class;
    }
}

<?php namespace Lovata\FacebookShopaholic\Classes\Event\Product;

use Lovata\FacebookShopaholic\Models\FacebookSettings;
use Lovata\Toolbox\Classes\Event\AbstractBackendFieldHandler;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Controllers\Products;

/**
 * Class ExtendProductFieldsHandler
 *
 * @package Lovata\FacebookShopaholic\Classes\Event\Product
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExtendProductFieldsHandler extends AbstractBackendFieldHandler
{
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
        $arFields = [];

        $sCodeModelForImages = FacebookSettings::getValue('code_model_for_images', '');

        if (!empty($sCodeModelForImages) && $sCodeModelForImages == FacebookSettings::CODE_PRODUCT) {
            $arFields['section_facebook'] = [
                'label' => 'lovata.facebookshopaholic::lang.field.section_facebook',
                'type'  => 'section',
                'span'  => 'left',
                'tab'   => 'lovata.toolbox::lang.tab.images',
            ];
            $arFields['preview_image_facebook'] = [
                'label'     => 'lovata.toolbox::lang.field.preview_image',
                'type'      => 'fileupload',
                'span'      => 'left',
                'required'  => true,
                'mode'      => 'image',
                'tab'       => 'lovata.toolbox::lang.tab.images',
                'fileTypes' => 'jpeg,png',
            ];
            $arFields['images_facebook'] = [
                'label'     => 'lovata.toolbox::lang.field.images',
                'type'      => 'fileupload',
                'span'      => 'left',
                'required'  => false,
                'mode'      => 'image',
                'tab'       => 'lovata.toolbox::lang.tab.images',
                'fileTypes' => 'jpeg,png',
            ];
        }

        $obWidget->addTabFields($arFields);
    }
}

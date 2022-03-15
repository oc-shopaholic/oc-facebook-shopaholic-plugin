<?php namespace Lovata\FacebookShopaholic\Models;

use Lang;
use System\Classes\PluginManager;
use October\Rain\Database\Traits\Validation;

use Lovata\Toolbox\Models\CommonSettings;

/**
 * Class FacebookSettings
 *
 * @package Lovata\FacebookShopaholic\Models
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 *
 * @mixin \October\Rain\Database\Builder
 * @mixin \Eloquent
 * @mixin \System\Behaviors\SettingsModel
 */
class FacebookSettings extends CommonSettings
{
    use Validation;

    const SETTINGS_CODE = 'lovata_shopaholic_facebook_export_settings';

    const CODE_OFFER   = 'offer';
    const CODE_PRODUCT = 'product';

    const FACEBOOK_PROPERTY_COLOR    = 'color';
    const FACEBOOK_PROPERTY_MATERIAL = 'material';
    const FACEBOOK_PROPERTY_SIZE     = 'size';

    /**
     * @var string
     */
    public $settingsCode = 'lovata_shopaholic_facebook_export_settings';

    /**
     * @var array
     */
    public $rules = [
        'store_name' => 'required',
        'store_url'  => 'required',
    ];

    /**
     * @var array
     */
    public $attributeNames = [
        'store_name' => 'lovata.toolbox::lang.field.name',
        'store_url'  => 'lovata.toolbox::lang.field.url',
    ];

    /**
     * Get model potions
     *
     * @return array
     */
    public function getGetImagesFromOptions()
    {
        return [
            self::CODE_OFFER   => Lang::get('lovata.shopaholic::lang.field.offer'),
            self::CODE_PRODUCT => Lang::get('lovata.toolbox::lang.field.product'),
        ];
    }

    /**
     * Get offer properties options
     *
     * @return array
     */
    public function getOfferPropertiesOptions()
    {
        if (!PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic')) {
            return [];
        }

        $arPropertyList = \Lovata\PropertiesShopaholic\Models\Property::pluck('name', 'id')->all();

        return $arPropertyList;
    }

    /**
     * Get facebook properties options
     *
     * @return array
     */
    public function getFacebookPropertiesOptions()
    {
        return [
            self::FACEBOOK_PROPERTY_COLOR    => Lang::get('lovata.facebookshopaholic::lang.field.property_color'),
            self::FACEBOOK_PROPERTY_MATERIAL => Lang::get('lovata.facebookshopaholic::lang.field.property_material'),
            self::FACEBOOK_PROPERTY_SIZE     => Lang::get('lovata.facebookshopaholic::lang.field.property_size'),
        ];
    }
}

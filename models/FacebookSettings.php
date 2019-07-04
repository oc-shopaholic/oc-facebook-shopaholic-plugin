<?php namespace Lovata\FacebookShopaholic\Models;

use October\Rain\Database\Traits\Validation;

use Lovata\Toolbox\Models\CommonSettings;
use System\Classes\PluginManager;

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
        'name' => 'required',
        'url'  => 'required',
    ];

    /**
     * @var array
     */
    public $attributeNames = [
        'name' => 'lovata.toolbox::lang.field.name',
        'url'  => 'lovata.toolbox::lang.field.url',
    ];

    /**
     * Get model potions
     *
     * @return array
     */
    public function getWhereToGetTheImagesOptions()
    {
        return [
            self::CODE_OFFER   => trans('lovata.shopaholic::lang.field.offer'),
            self::CODE_PRODUCT => trans('lovata.toolbox::lang.field.product'),
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

        $arPropertyList = \Lovata\PropertiesShopaholic\Models\Property::lists('name', 'id');

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
            self::FACEBOOK_PROPERTY_COLOR    => trans('lovata.facebookshopaholic::lang.field.property_color'),
            self::FACEBOOK_PROPERTY_MATERIAL => trans('lovata.facebookshopaholic::lang.field.property_material'),
            self::FACEBOOK_PROPERTY_SIZE     => trans('lovata.facebookshopaholic::lang.field.property_size'),
        ];
    }
}

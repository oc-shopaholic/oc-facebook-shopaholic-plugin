<?php namespace Lovata\FacebookShopaholic;

use Event;
use System\Classes\PluginBase;

// Command
use Lovata\FacebookShopaholic\Classes\Console\CatalogExportForFacebook;

// Offer event
use Lovata\FacebookShopaholic\Classes\Event\Offer\ExtendOfferFieldsHandler;
use Lovata\FacebookShopaholic\Classes\Event\Offer\OfferModelHandler;
// Product event
use Lovata\FacebookShopaholic\Classes\Event\Product\ExtendProductFieldsHandler;
use Lovata\FacebookShopaholic\Classes\Event\Product\ProductModelHandler;

/**
 * Class Plugin
 *
 * @package Lovata\FacebookShopaholic
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class Plugin extends PluginBase
{
    /**
     * Register settings
     * @return array
     */
    public function registerSettings()
    {
        return [
            'config'    => [
                'label'       => 'lovata.facebookshopaholic::lang.menu.facebooksettings',
                'description' => '',
                'category'    => 'lovata.shopaholic::lang.tab.settings',
                'icon'        => 'icon-upload',
                'class'       => 'Lovata\FacebookShopaholic\Models\FacebookSettings',
                'permissions' => ['shopaholic-menu-facebook-export'],
                'order'       => 9000,
            ],
        ];
    }

    /**
     * Plugin boot method
     */
    public function boot()
    {
        // Offer event
        Event::subscribe(ExtendOfferFieldsHandler::class);
        Event::subscribe(OfferModelHandler::class);
        // Product event
        Event::subscribe(ExtendProductFieldsHandler::class);
        Event::subscribe(ProductModelHandler::class);
    }

    /**
     * Register artisan command
     */
    public function register()
    {
        $this->registerConsoleCommand('shopaholic:catalog_export_to_facebook', CatalogExportForFacebook::class);
    }

    /**
     * @return array
     */
    public function registerReportWidgets()
    {
        return [
            'Lovata\FacebookShopaholic\Widgets\ExportToXML' => [
                'label' => 'lovata.facebookshopaholic::lang.widget.export_catalog_to_xml_for_facebook',
            ],
        ];
    }
}

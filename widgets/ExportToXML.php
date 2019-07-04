<?php namespace Lovata\FacebookShopaholic\Widgets;

use Flash;
use Artisan;
use Backend\Classes\ReportWidgetBase;

/**
 * Class ExportToXML
 * @package Lovata\FacebookShopaholic\Widgets
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExportToXML extends ReportWidgetBase
{
    /**
     * Render method
     * @return mixed|string
     * @throws \SystemException
     */
    public function render()
    {
        return $this->makePartial('widget');
    }

    /**
     * Generate xml for facebook
     */
    public function onGenerateXMLForFacebook()
    {
        Artisan::call('shopaholic:catalog_export_to_facebook');
        Flash::info(trans('lovata.facebookshopaholic::lang.message.export_is_complete'));
    }
}

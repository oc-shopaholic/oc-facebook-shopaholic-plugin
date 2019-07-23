<?php namespace Lovata\FacebookShopaholic\Widgets;

use Flash;
use Storage;
use Backend\Classes\ReportWidgetBase;
use Lovata\FacebookShopaholic\Classes\Helper\ExportCatalogHelper;
use Lovata\FacebookShopaholic\Classes\Helper\GenerateXML;

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
        $this->vars['sFileUrl'] = $this->getFileUrl();

        return $this->makePartial('widget');
    }

    /**
     * Generate xml for facebook
     */
    public function onGenerateXMLFileFacebook()
    {
        $obDataCollection = new ExportCatalogHelper();
        $obDataCollection->run();

        Flash::info(trans('lovata.facebookshopaholic::lang.message.export_is_completed'));

        $this->vars['sFileUrl'] = $this->getFileUrl();
    }

    /**
     * Get fie url
     *
     * @return string
     */
    protected function getFileUrl()
    {
        $sFilePath = GenerateXML::getFilePath();
        $sFullFilePath = storage_path($sFilePath);
        if (!file_exists($sFullFilePath)) {
            return null;
        }

        $sStorageFilePath = Storage::url($sFilePath);

        return $sStorageFilePath;
    }
}

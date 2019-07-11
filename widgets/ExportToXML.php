<?php namespace Lovata\FacebookShopaholic\Widgets;

use Flash;
use Backend\Classes\ReportWidgetBase;
use Lovata\FacebookShopaholic\Classes\Helper\DataCollection;
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
    public function onGenerateXMLForFacebook()
    {
        $obDataCollection = new DataCollection();
        $obDataCollection->generate();

        Flash::info(trans('lovata.facebookshopaholic::lang.message.export_is_complete'));

        $this->vars['sFileUrl'] = $this->getFileUrl();
    }

    /**
     * Get fie url
     *
     * @return string
     */
    protected function getFileUrl()
    {
        $sAppUrl = config('app.url');
        $sMediaFilePath = GenerateXML::getMediaPath().GenerateXML::FILE_NAME;
        $sStorageMediaFilePath = storage_path($sMediaFilePath);

        if (!file_exists($sStorageMediaFilePath)) {
            return '';
        }

        $sStorageFilePath = \Storage::url($sMediaFilePath);

        return $sAppUrl.$sStorageFilePath;
    }
}

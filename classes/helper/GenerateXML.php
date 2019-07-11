<?php namespace Lovata\FacebookShopaholic\Classes\Helper;

use File;
use XMLWriter;
use Lovata\FacebookShopaholic\Models\FacebookSettings as Config;

/**
 * Class GenerateXML
 *
 * @package Lovata\FacebookShopaholic\Classes\Helper
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class GenerateXML
{
    const FILE_NAME = 'facebook_atom.xml';

    const DEFAULT_DIRECTORY = 'app/media/';

    /**
     * @var array
     */
    protected $arShopData = [];

    /**
     * @var array
     */
    protected $arOffersData = [];

    /**
     * Generated content
     */
    protected $sContent;

    /**
     * @var XMLWriter
     */
    protected $obXMLWriter;

    /**
     * Generate
     *
     * @param array $arData
     */
    public function generate($arData)
    {
        $this->arShopData   = array_get($arData, 'shop', []);
        $this->arOffersData = array_get($arData, 'offers', []);

        if (empty($this->arShopData) || empty($this->arOffersData)) {
            return;
        }

        $this->start();
        $this->setContent();
        $this->stop();

        $this->save();
    }

    /**
     * Get media path
     *
     * @return string
     */
    public static function getMediaPath()
    {
        $sMediaPath = self::DEFAULT_DIRECTORY;

        $sFilePath = (string) Config::getValue('path_to_export_the_file' , '');

        if (empty($sFilePath)) {
            return $sMediaPath;
        }

        $sFilePath = trim($sFilePath);
        $sFilePath = preg_replace('/^\/+/', '', $sFilePath);
        $sFilePath = preg_replace('/\/+$/', '', $sFilePath);
        $sFilePath = trim($sFilePath);
        $sFilePath = preg_replace('/ +/', '', $sFilePath);
        $sFilePath .= '/';

        return $sMediaPath.$sFilePath;
    }

    /**
     * Set content
     */
    protected function setContent()
    {
        $this->setDataShop();
        $this->setDataEntryList();
    }

    /**
     * Set data shop
     */
    protected function setDataShop()
    {
        if (empty($this->arShopData) || empty($this->obXMLWriter)) {
            return;
        }

        // <title>
        $this->obXMLWriter->writeElement('title', array_get($this->arShopData, 'title'));
        // <link>
        $this->obXMLWriter->writeElement('link', array_get($this->arShopData, 'link'));
    }

    /**
     * Set data offer list
     */
    protected function setDataEntryList()
    {
        if (empty($this->arOffersData) || !is_array($this->arOffersData) || empty($this->obXMLWriter)) {
            return;
        }

        foreach ($this->arOffersData as $arOffer) {
            // <entry>
            $this->obXMLWriter->startElement('entry');
            $this->setDataEntry($arOffer);
            // </entry>
            $this->obXMLWriter->endElement();
        }
    }

    /**
     * Set data item list
     * @param array $arOffer
     */
    protected function setDataEntry($arOffer)
    {
        if (empty($arOffer) || !is_array($arOffer)) {
            return;
        }

        $sOldPrice      = array_get($arOffer, 'old_price');
        $sProductType   = array_get($arOffer, 'product_type');
        $sBrandName     = array_get($arOffer, 'brand_name');
        $arImageList    = array_get($arOffer, 'images');
        $arPropertyList = array_get($arOffer, 'properties');

        // <g:id>
        $this->obXMLWriter->writeElement('g:id', array_get($arOffer, 'id'));
        // </g:id>
        // <g:availability>
        $this->obXMLWriter->writeElement('g:availability', array_get($arOffer, 'availability'));
        // </g:availability>
        // <g:condition>
        $this->obXMLWriter->writeElement('g:condition', array_get($arOffer, 'condition'));
        // </g:condition>
        // <g:description>
        $this->obXMLWriter->writeElement('g:description', array_get($arOffer, 'description'));
        // </g:description>
        // <g:image_link>
        $this->obXMLWriter->writeElement('g:image_link', array_get($arOffer, 'preview_image'));
        // </g:image_link>
        // <g:link>
        $this->obXMLWriter->writeElement('g:link', array_get($arOffer, 'url'));
        // </g:link>
        // <g:title>
        $this->obXMLWriter->writeElement('g:title', array_get($arOffer, 'name'));
        // </g:title>
        // <g:price>
        $this->obXMLWriter->writeElement('g:price', array_get($arOffer, 'price'));
        // </g:price>
        if (!empty($sOldPrice)) {
            // <g:sale_price>
            $this->obXMLWriter->writeElement('g:sale_price', $sOldPrice);
            // </g:sale_price>
        }
        if (!empty($sBrandName)) {
            // <g:brand>
            $this->obXMLWriter->writeElement('g:brand', $sBrandName);
            // </g:brand>
        }
        if (!empty($sProductType)) {
            // <g:product_type>
            $this->obXMLWriter->writeElement('g:product_type', $sProductType);
            // </g:product_type>
        }
        if (!empty($arImageList) && is_array($arImageList)) {
            foreach ($arImageList as $sImage) {
                // additional_image_link>
                $this->obXMLWriter->writeElement('additional_image_link', $sImage);
                // <additional_image_link>
            }
        }
        if (!empty($arPropertyList) && is_array($arPropertyList)) {
            foreach ($arPropertyList as $arProperty) {
                $sCode = key($arProperty);
                $sProperty = array_shift($arProperty);
                // <property>
                $this->obXMLWriter->writeElement($sCode, $sProperty);
                // <property>
            }
        }
    }

    /**
     * Start xml content generation
     */
    protected function start()
    {
        $this->obXMLWriter = new XMLWriter();
        $this->obXMLWriter->openMemory();
        $this->obXMLWriter->setIndent(1);
        $this->obXMLWriter->startDocument('1.0', 'UTF-8');
        $this->obXMLWriter->startElement('feed');
        $this->obXMLWriter->writeAttribute('xmlns', 'http://www.w3.org/2005/Atom');
        $this->obXMLWriter->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
    }

    /**
     * End xml content generation
     */
    protected function stop()
    {
        $this->obXMLWriter->endElement();
        $this->obXMLWriter->endDocument();
        $this->sContent = $this->obXMLWriter->outputMemory();
    }

    /**
     * Save generated content
     */
    protected function save()
    {
        $sMediaPath = self::getMediaPath();

        $sFilePath = storage_path($sMediaPath);

        if (!file_exists($sFilePath)) {
            mkdir($sFilePath, 0777, true);
        }

        $sFile = $sFilePath.self::FILE_NAME;

        File::put($sFile, $this->sContent);
    }
}

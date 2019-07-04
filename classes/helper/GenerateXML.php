<?php namespace Lovata\FacebookShopaholic\Classes\Helper;

use File;
use XMLWriter;
use October\Rain\Argon\Argon;
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
        $this->stop();
        $this->save();
    }

    /**
     * Get file path
     *
     * @return string
     */
    public static function getFilePath()
    {
        $sFilePath = (string) Config::getValue('path_to_export_the_file' , '');

        if (empty($sFilePath)) {
            return '/';
        }

        $sFilePath = trim($sFilePath);
        $sFilePath = preg_replace('/^\//', '', $sFilePath);
        $sFilePath = preg_replace('/\/$/', '', $sFilePath);
        $sFilePath = trim($sFilePath);
        $sFilePath = preg_replace('/ +/', '', $sFilePath);
        $sFilePath .= '/';

        return $sFilePath;
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
        $this->obXMLWriter->startElement('yml_catalog');
        $this->obXMLWriter->writeAttribute('date', Argon::now()->format('Y-m-d h:i'));
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
        $sFilePath = self::getFilePath();

        $sFilePath = base_path($sFilePath);

        if (!file_exists($sFilePath)) {
            mkdir($sFilePath, null, true);
        }

        $sFile = $sFilePath.self::FILE_NAME;

        File::put($sFile, $this->sContent);
    }
}

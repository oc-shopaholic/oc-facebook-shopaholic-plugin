<?php namespace Lovata\FacebookShopaholic\Classes\Console;

use Illuminate\Console\Command;
use Lovata\FacebookShopaholic\Classes\Helper\ExportCatalogHelper;

/**
 * Class CatalogExportForFacebook
 *
 * @package Lovata\FacebookShopaholic\Classes\Console
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class CatalogExportForFacebook extends Command
{
    /**
     * @var string command name.
     */
    protected $name = 'shopaholic:catalog_export.facebook';

    /**
     * @var string The console command description.
     */
    protected $description = 'Generate xml file for Facebook';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        $obDataCollection = new ExportCatalogHelper();
        $obDataCollection->run();
    }
}

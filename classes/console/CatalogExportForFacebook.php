<?php namespace Lovata\FacebookShopaholic\Classes\Console;

use Illuminate\Console\Command;
use Lovata\FacebookShopaholic\Classes\Helper\DataCollection;

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
    protected $name = 'shopaholic:catalog_export_to_facebook';

    /**
     * @var string The console command description.
     */
    protected $description = 'Run catalog export to Facebook';

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle()
    {
        $obDataCollection = new DataCollection();
        $obDataCollection->generate();
    }
}

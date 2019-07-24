<?php namespace Lovata\FacebookShopaholic\Classes\Event\Product;

use Lovata\Shopaholic\Models\Product;

/**
 * Class ProductModelHandler
 *
 * @package Lovata\FacebookShopaholic\Classes\Event\Product
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ProductModelHandler
{
    /**
     * Extend Product model
     */
    public function subscribe()
    {
        Product::extend(function ($obProduct) {
            /** @var Product $obProduct */
            $obProduct->fillable[] = 'preview_image_facebook';
            $obProduct->fillable[] = 'images_facebook';

            $obProduct->attachOne['preview_image_facebook'] = 'System\Models\File';
            $obProduct->attachMany['images_facebook'] = 'System\Models\File';

            $obProduct->addCachedField(['preview_image_facebook', 'images_facebook']);
        });
    }
}

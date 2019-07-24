<?php namespace Lovata\FacebookShopaholic\Classes\Event\Offer;

use Lovata\Shopaholic\Models\Offer;

/**
 * Class OfferModelHandler
 *
 * @package Lovata\FacebookShopaholic\Classes\Event\Offer
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class OfferModelHandler
{
    /**
     * Extend Offer model
     */
    public function subscribe()
    {
        Offer::extend(function ($obOffer) {
            /** @var Offer $obOffer */
            $obOffer->fillable[] = 'preview_image_facebook';
            $obOffer->fillable[] = 'images_facebook';

            $obOffer->attachOne['preview_image_facebook'] = 'System\Models\File';
            $obOffer->attachMany['images_facebook'] = 'System\Models\File';

            $obOffer->addCachedField(['preview_image_facebook', 'images_facebook']);
        });
    }
}

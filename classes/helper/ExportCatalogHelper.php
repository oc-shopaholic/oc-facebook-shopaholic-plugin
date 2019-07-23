<?php namespace Lovata\FacebookShopaholic\Classes\Helper;

use Event;
use System\Classes\PluginManager;

use Lovata\Shopaholic\Models\Currency;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;

use Lovata\PropertiesShopaholic\Classes\Item\PropertyItem;
use Lovata\FacebookShopaholic\Models\FacebookSettings;

/**
 * Class ExportCatalogHelper
 *
 * @package Lovata\FacebookShopaholic\Classes\Helper
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class ExportCatalogHelper
{
    const EVENT_FACEBOOK_SHOP_DATA = 'shopaholic.facebook.shop.data';
    const EVENT_FACEBOOK_OFFER_DATA = 'shopaholic.facebook.offer.data';

    /**
     * @var array
     */
    protected $arConfig = [];

    /**
     * @var array
     * $arData = [
     *     'shop'   => [
     *              'name' => '',
     *              'url'  =>  '',
     *          ],
     *     ],
     *     'offers' => [
     *          [
     *              'id'             => '',
     *               'availability'  => '',
     *               'condition'     => 'new',
     *               'description'   => '',
     *               'preview_image' => '',
     *               'images'        => [],
     *               'url'           => '',
     *               'name'          => '',
     *               'price'         => '',
     *               'old_price'     => '',
     *               'brand_name'    => '',
     *               'product_type'  => '',
     *               'properties'    => [
     *                   'color'    => '',
     *                   'material' => '',
     *                   'size'     => '',
     *               ],
     *          ],
     *     ],
     * ]
     */
    protected $arData = [
        'shop'   => [],
        'offers' => [],
    ];

    /**
     * @var Currency
     */
    protected $obDefaultCurrency;

    /**
     * Generate XML file
     */
    public function run()
    {
        //Prepare data
        $this->initShopData();
        $this->initProductListData();

        //Generate XML file
        $obGenerateXML = new GenerateXML();
        $obGenerateXML->generate($this->arData);
    }

    /**
     * Init shop data
     */
    protected function initShopData()
    {
        array_set($this->arData, 'shop.name', FacebookSettings::getValue('store_name'));
        array_set($this->arData, 'shop.company', FacebookSettings::getValue('store_url'));

        $arShopData = array_get($this->arData, 'shop');
        $arEventData = Event::fire(self::EVENT_FACEBOOK_SHOP_DATA, [$arShopData]);
        if (empty($arEventData)) {
            return;
        }

        foreach ($arEventData as $arEventShopData) {
            if (empty($arEventShopData) || !is_array($arEventShopData)) {
                continue;
            }

            $arShopData = array_merge($arShopData, $arEventShopData);
        }

        $this->arData['shop'] = $arShopData;
    }

    /**
     * Init product list data
     */
    protected function initProductListData()
    {
        $obProductList = ProductCollection::make()->active();
        if ($obProductList->isEmpty()) {
            return;
        }

        $this->obDefaultCurrency = Currency::isDefault()->first();

        /** @var ProductItem $obProduct */
        foreach ($obProductList as $obProduct) {
            $this->initOfferListData($obProduct);
        }
    }

    /**
     * Init offers data
     *
     * @param ProductItem $obProduct $obProduct
     */
    protected function initOfferListData($obProduct)
    {
        $obOfferList = $obProduct->offer;
        if ($obOfferList->isEmpty()) {
            return;
        }

        foreach ($obOfferList as $obOffer) {
            $this->initOffer($obOffer, $obProduct);
        }
    }

    /**
     * Init offer
     *
     * @param OfferItem   $obOffer
     * @param ProductItem $obProduct
     */
    protected function initOffer($obOffer, $obProduct)
    {
        $sPrice = $obOffer->price;
        if (!empty($this->obDefaultCurrency)) {
            $sPrice .= ' '.$this->obDefaultCurrency->code;
        }

        $arOfferData = [
            'id'            => $obOffer->id,
            'availability'  => $this->getOfferAvailability($obOffer),
            'condition'     => 'new',
            'description'   => $obOffer->preview_text,
            'preview_image' => $this->getOfferPreviewImage($obOffer, $obProduct),
            'images'        => $this->getOfferImages($obOffer, $obProduct),
            'url'           => $obProduct->getPageUrl(),
            'name'          => $obOffer->name,
            'price'         => $sPrice,
            'product_type'  => $this->getOfferCategory($obProduct),
            'brand_name'    => $this->getBrandName($obProduct),
            'old_price'     => $this->getOfferOldPrice($obOffer),
            'properties'    => $this->getOfferProperties($obOffer),
        ];

        $arEventData = Event::fire(self::EVENT_FACEBOOK_OFFER_DATA, [$arOfferData]);
        if (!empty($arEventData)) {
            foreach ($arEventData as $arEventOfferData) {
                if (empty($arEventOfferData) || !is_array($arEventOfferData)) {
                    continue;
                }

                $arOfferData = array_merge($arOfferData, $arEventOfferData);
            }
        }

        $this->arData['offers'][] = $arOfferData;
    }

    /**
     * Get offer old price
     *
     * @param OfferItem $obOffer
     * @return string|void
     */
    protected function getOfferOldPrice($obOffer)
    {
        $bFieldOldPrice = FacebookSettings::getValue('field_old_price', false);
        if (!$bFieldOldPrice || $obOffer->old_price_value == 0) {
            return '';
        }

        $sResult = $obOffer->old_price;
        if (!empty($this->obDefaultCurrency)) {
            $sResult .= ' '.$this->obDefaultCurrency->code;
        }

        return $sResult;
    }

    /**
     * Get brand name
     *
     * @param ProductItem $obProduct
     * @return string
     */
    protected function getBrandName($obProduct)
    {
        $bFieldBrand = FacebookSettings::getValue('field_brand', false);
        if (!$bFieldBrand || $obProduct->brand->isEmpty()) {
            return '';
        }

        return $obProduct->brand->name;
    }

    /**
     * Get offer category
     *
     * @param ProductItem $obProduct
     * @return string
     */
    protected function getOfferCategory($obProduct)
    {
        if ($obProduct->category->isEmpty()) {
            return '';
        }

        if ($obProduct->category->parent->isEmpty()) {
            return $obProduct->category->name;
        }

        return $obProduct->category->parent->name.' > '.$obProduct->category->name;
    }

    /**
     * Get offer preview image
     *
     * @param OfferItem   $obOffer
     * @param ProductItem $obProduct
     *
     * @return string
     */
    protected function getOfferPreviewImage($obOffer, $obProduct)
    {
        $sCodeModelForImages = FacebookSettings::getValue('code_model_for_images', '');
        if (empty($sCodeModelForImages)) {
            return '';
        }

        if (FacebookSettings::CODE_OFFER == $sCodeModelForImages) {
            $obItem = $obOffer;
        } else {
            $obItem = $obProduct;
        }

        if (empty($obItem->preview_image_facebook)) {
            return '';
        }

        return $obItem->preview_image_facebook->getPath();
    }

    /**
     * Get offer availability
     *
     * @param OfferItem $obOffer
     * @return string
     */
    public function getOfferAvailability($obOffer)
    {
        $sResult = $obOffer->quantity = 0 ? 'out of stock' : 'in stock';

        return $sResult;
    }

    /**
     * Get offer images
     *
     * @param OfferItem|OfferItem   $obOffer
     * @param OfferItem|ProductItem $obProduct
     *
     * @return array
     */
    protected function getOfferImages($obOffer, $obProduct)
    {
        $arResult = [];

        $sCodeModelForImages = FacebookSettings::getValue('code_model_for_images', '');
        $bFieldImages = FacebookSettings::getValue('field_images', false);
        if (empty($sCodeModelForImages) || !$bFieldImages) {
            return $arResult;
        }

        if (FacebookSettings::CODE_OFFER == $sCodeModelForImages) {
            $obItem = $obOffer;
        } else {
            $obItem = $obProduct;
        }

        if ($obItem->images_facebook->isEmpty()) {
            return $arResult;
        }

        foreach ($obItem->images_facebook as $obImage) {
            $arResult[] = $obImage->getPath();
        }

        return $arResult;
    }

    /**
     * Get offer property
     *
     * @param OfferItem $obOffer
     * @return array
     */
    protected function getOfferProperties($obOffer)
    {
        $arResult = [];
        $bHasPlugin = PluginManager::instance()->hasPlugin('Lovata.PropertiesShopaholic');
        if (!$bHasPlugin) {
            return $arResult;
        }

        $obPropertyList = $obOffer->property;
        $arPropertyRatio = (array) FacebookSettings::getValue('field_offer_properties', []);
        if ($obPropertyList->isEmpty() || empty($arPropertyRatio)) {
            return $arResult;
        }


        /** @var PropertyItem $obPropertyItem */
        foreach ($obPropertyList as $obPropertyItem) {
            if (!$obPropertyItem->hasValue()) {
                continue;
            }

            $arProperty = $this->getProperty($obPropertyItem, $arPropertyRatio);
            if (!empty($arProperty)) {
                $arResult[] = $arProperty;
            }
        }

        return $arResult;
    }

    /**
     * Get property
     *
     * @param PropertyItem      $obPropertyItem
     * @param array             $arPropertyRatio
     *
     * @return array
     */
    protected function getProperty($obPropertyItem, $arPropertyRatio)
    {
        foreach ($arPropertyRatio as $arRation) {
            $sPropertyCode = array_get($arRation, 'facebook_property_code', null);
            $iPropertyId = array_get($arRation, 'property_id', null);
            if (empty($iPropertyId) || empty($sPropertyCode) || $obPropertyItem->id != $iPropertyId) {
                continue;
            }

            return [$sPropertyCode => $obPropertyItem->property_value->getValueString()];
        }

        return [];
    }
}

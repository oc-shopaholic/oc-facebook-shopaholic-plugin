<?php namespace Lovata\FacebookShopaholic\Classes\Helper;

use Event;
use Lovata\PropertiesShopaholic\Classes\Item\PropertyItem;
use Lovata\PropertiesShopaholic\Classes\Item\PropertyValueItem;
use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Shopaholic\Classes\Item\OfferItem;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Lovata\FacebookShopaholic\Models\FacebookSettings as Config;
use Lovata\Shopaholic\Models\Currency;
use System\Classes\PluginManager;

/**
 * Class DataCollection
 *
 * @package Lovata\FacebookShopaholic\Classes\Helper
 * @author  Sergey Zakharevich, s.zakharevich@lovata.com, LOVATA Group
 */
class DataCollection
{
    const EVENT_FACEBOOK_SHOP_DATA  = 'shopaholic.facebook.shop.data';
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
    protected $arData = [];

    /**
     * @var Currency
     */
    protected $obDefaultCurrency;

    /**
     * Generate
     */
    public function generate()
    {
        $this->initShopData();
        $this->initProductListData();

        $obGenerateXML = new GenerateXML();

        $obGenerateXML->generate($this->arData);
    }

    /**
     * Init shop data
     */
    protected function initShopData()
    {
        if (!is_array($this->arData)) {
            return;
        }

        array_set($this->arData, 'shop.name', Config::getValue('store_name'));
        array_set($this->arData, 'shop.company', Config::getValue('store_url'));

        $arEventShopData = Event::fire(
            self::EVENT_FACEBOOK_SHOP_DATA,
            [array_get($this->arData, 'shop')],
            true
        );

        if (!empty($arEventShopData) && is_array($arEventShopData)) {
            array_set($this->arData, 'shop', $arEventShopData);
        }
    }

    /**
     * Init product list data
     */
    protected function initProductListData()
    {
        if (!is_array($this->arData)) {
            return;
        }

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
        if (empty($obProduct) || !$obProduct instanceof ProductItem) {
            return;
        }

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
     * @param OfferItem $obOffer
     * @param ProductItem $obProduct
     */
    protected function initOffer($obOffer, $obProduct)
    {
        $bOffer   = empty($obOffer) || !$obOffer instanceof OfferItem;
        $bProduct = empty($obProduct) || !$obProduct instanceof ProductItem;
        if ($bOffer || $bProduct || !is_array($this->arData) || empty($this->obDefaultCurrency)) {
            return;
        }

        $arOfferList = array_pull($this->arData, 'offers', []);
        $arOffer = [
            'id'            => $obOffer->id,
            'availability'  => $this->getOfferAvailability($obOffer),
            'condition'     => 'new',
            'description'   => $obOffer->preview_text,
            'preview_image' => $this->getOfferPreviewImage($obOffer, $obProduct),
            'images'        => $this->getOfferImages($obOffer, $obProduct),
            'url'           => $obProduct->getPageUrl(),
            'name'          => $obOffer->name,
            'price'         => $obOffer->price.' '.$this->obDefaultCurrency->code,
            'product_type'  => $this->getOfferCategory($obProduct),
            'brand_name'    => $this->getBrandName($obProduct),
            'old_price'     => $this->getOfferOldPrice($obOffer),
            'properties'    => $this->getOfferProperties($obOffer),
        ];

        $arEventOfferData = Event::fire(self::EVENT_FACEBOOK_OFFER_DATA, [$arOffer], true);

        if (!empty($arEventOfferData) && is_array($arEventOfferData)) {
            $arOffer = $arEventOfferData;
        }

        $arOfferList[] = $arOffer;

        array_set($this->arData, 'offers', $arOfferList);
    }

    /**
     * Get offer old price
     *
     * @param OfferItem $obOffer
     * @return string|void
     */
    protected function getOfferOldPrice($obOffer)
    {
        $bFieldOldPrice = Config::getValue('field_old_price', false);

        if (!$bFieldOldPrice || !$obOffer instanceof OfferItem || $obOffer->old_price == 0) {
            return '';
        }

        return $obOffer->old_price.' '.$this->obDefaultCurrency->code;
    }
    /**
     * Get brand name
     *
     * @param ProductItem $obProduct
     * @return string
     */
    protected function getBrandName($obProduct)
    {
        $bFieldBrand = Config::getValue('field_brand', false);

        if (!$bFieldBrand || !$obProduct instanceof ProductItem || $obProduct->brand->isEmpty()) {
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
        if (empty($obProduct) || !$obProduct instanceof ProductItem || empty($obProduct->category)) {
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
     * @param OfferItem|OfferItem $obOffer
     * @param OfferItem|ProductItem $obProduct
     *
     * @return string
     */
    protected function getOfferPreviewImage($obOffer, $obProduct)
    {
        $sCodeModelForImages = Config::getValue('code_model_for_images', '');

        if (empty($sCodeModelForImages)) {
            return '';
        }

        if (Config::CODE_OFFER == $sCodeModelForImages) {
            $obItem = $obOffer;
        } else {
            $obItem = $obProduct;
        }

        if (empty($obItem) || (!$obItem instanceof OfferItem && !$obItem instanceof ProductItem)) {
            return '';
        }

        $obModel = $obItem->getObject();

        if (empty($obModel) || empty($obModel->preview_image_facebook)) {
            return '';
        }

        return $obModel->preview_image_facebook->path;
    }

    /**
     * Get offer availability
     *
     * @param OfferItem $obOffer
     * @return string
     */
    public function getOfferAvailability($obOffer)
    {
        if (empty($obOffer) || !$obOffer instanceof OfferItem || $obOffer->quantity = 0) {
            return 'out of stock';
        }

        return 'in stock';
    }

    /**
     * Get offer images
     *
     * @param OfferItem|OfferItem $obOffer
     * @param OfferItem|ProductItem $obProduct
     *
     * @return array
     */
    protected function getOfferImages($obOffer, $obProduct)
    {
        $arResult = [];

        $sCodeModelForImages = Config::getValue('code_model_for_images', '');
        $bFieldImages        = Config::getValue('field_images', false);

        if (empty($sCodeModelForImages)) {
            return $arResult;
        }

        if (Config::CODE_OFFER == $sCodeModelForImages) {
            $obItem = $obOffer;
        } else {
            $obItem = $obProduct;
        }

        if (!$bFieldImages || empty($obItem) || (!$obItem instanceof OfferItem && !$obItem instanceof ProductItem)) {
            return $arResult;
        }

        $obModel = $obItem->getObject();

        if (empty($obModel) || $obModel->images_facebook->isEmpty()) {
            return $arResult;
        }

        foreach ($obModel->images_facebook as $obImage) {
            $arResult[] = $obImage->path;
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

        if (!$bHasPlugin || empty($obOffer) || !$obOffer instanceof OfferItem) {
            return $arResult;
        }

        $obPropertyList = $obOffer->property;

        if ($obPropertyList->isEmpty()) {
            return $arResult;
        }

        $arPropertyRatio = Config::getValue('field_offer_properties', []);

        /** @var PropertyItem $obPropertyItem */
        foreach ($obPropertyList as $obPropertyItem) {
            if (!$obPropertyItem->hasValue()) {
                continue;
            }

            /** @var PropertyValueItem $obPropertyValueItem */
            $obPropertyValueItem = $obPropertyItem->property_value->first();

            if ($obPropertyValueItem->isEmpty()) {
                continue;
            }

            $arProperty = $this->getProperty($obPropertyItem, $obPropertyValueItem, $arPropertyRatio);

            if (!empty($arProperty)) {
                $arResult[] = $arProperty;
            }
        }

        return $arResult;
    }

    /**
     * Get property
     *
     * @param PropertyItem $obPropertyItem
     * @param PropertyValueItem $obPropertyValueItem
     *
     * @return array
     */
    public function getProperty($obPropertyItem, $obPropertyValueItem, $arPropertyRatio)
    {
        $bPropertyItem      = empty($obPropertyItem) || !$obPropertyItem instanceof PropertyItem;
        $bPropertyValueItem = empty($obPropertyValueItem) || !$obPropertyValueItem instanceof PropertyValueItem;

        if ($bPropertyItem || $bPropertyValueItem || empty($arPropertyRatio) || !is_array($arPropertyRatio)) {
            return [];
        }

        foreach ($arPropertyRatio as $arRation) {
            $sPropertyCode = array_get($arRation, 'facebook_property_code', null);
            $iPropertyId = array_get($arRation, 'property_id', null);

            if (empty($iPropertyId) || empty($sPropertyCode) || $obPropertyItem->id != $iPropertyId) {
                continue;
            }

            return [$sPropertyCode => $obPropertyValueItem->value];
        }

        return [];
    }
}

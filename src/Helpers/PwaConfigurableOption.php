<?php

namespace Webkul\PWA\Helpers;

use Webkul\Product\Helpers\ConfigurableOption;
use Webkul\Product\Models\Product;

class PwaConfigurableOption extends ConfigurableOption
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Price $price,
    ) {
    }

    /**
     * Returns the allowed variants JSON
     *
     * @param  Product  $product
     * @return float
     */
    public function getConfigurationConfig($product)
    {
        $options = $this->getOptions($product, $this->getAllowedVariants($product));

        $config = [
            'attributes'    => $this->getAttributesData($product, $options),
            'index'         => isset($options['index']) ? $options['index'] : [],
            'regular_price' => [
                'formated_price' => core()->currency($this->price->getMinimalPrice($product)),
                'price'          => $this->price->getMinimalPrice($product),
            ],
            'variant_prices' => $this->getVariantPrices($product),
            'variant_images' => $this->getVariantImages($product),
            'chooseText'     => trans('shop::app.products.choose-option'),
        ];

        return $config;
    }

    /**
     * Get product prices for configurable variations
     *
     * @param  Product  $product
     * @return array
     */
    protected function getVariantPrices($product)
    {
        $prices = [];

        foreach ($this->getAllowedVariants($product) as $variant) {
            if ($variant instanceof \Webkul\Product\Models\ProductFlat) {
                $variantId = $variant->product_id;
            } else {
                $variantId = $variant->id;
            }

            $prices[$variantId] = [
                'regular_price' => [
                    'formated_price' => core()->currency($variant->price),
                    'price'          => $variant->price,
                ],
                'final_price' => [
                    'formated_price' => core()->currency($this->price->getMinimalPrice($variant)),
                    'price'          => $this->price->getMinimalPrice($variant),
                ],
            ];
        }

        return $prices;
    }
}

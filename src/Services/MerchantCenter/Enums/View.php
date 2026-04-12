<?php

namespace Anibalealvarezs\GoogleApi\Services\MerchantCenter\Enums;

enum View: string
{
    case PRODUCT_PERFORMANCE_VIEW = 'product_performance_view';
    case NON_PRODUCT_PERFORMANCE_VIEW = 'non_product_performance_view';
    case PRODUCT_VIEW = 'product_view';
    case PRICE_COMPETITIVENESS_PRODUCT_VIEW = 'price_competitiveness_product_view';
    case PRICE_INSIGHTS_PRODUCT_VIEW = 'price_insights_product_view';
    case BEST_SELLERS_PRODUCT_CLUSTER_VIEW = 'best_sellers_product_cluster_view';
    case BEST_SELLERS_BRAND_VIEW = 'best_sellers_brand_view';
}

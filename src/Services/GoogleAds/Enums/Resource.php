<?php

namespace Anibalealvarezs\GoogleApi\Services\GoogleAds\Enums;

enum Resource: string
{
    case CUSTOMER = 'customer';
    case CAMPAIGN = 'campaign';
    case AD_GROUP = 'ad_group';
    case AD_GROUP_AD = 'ad_group_ad';
    case KEYWORD_VIEW = 'keyword_view';
    case SEARCH_TERM_VIEW = 'search_term_view';
    case SHOPPING_PRODUCT = 'shopping_product';
}

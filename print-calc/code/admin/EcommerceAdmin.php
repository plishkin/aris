<?php

namespace PC;

class EcommerceAdmin extends \ModelAdmin  {

    private static $allowed_actions = array(
    );

    private static $managed_models = array(
        'PC\Order',
        'PC\OrderDeadline',
        'PC\OrderStatus',
        'PC\ClientOrder',
        'PC\Currency',
        'PC\CurrencyRate',
        'PC\Calculation',
        'PC\CalculationRequest',
    );

    private static $url_segment = 'pc-ecommerce';
    private static $menu_title = 'PC Ecommerce';
    public $showImportForm = false;

}

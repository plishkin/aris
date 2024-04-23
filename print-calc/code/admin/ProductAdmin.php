<?php

namespace PC;

class ProductAdmin extends \ModelAdmin  {

    private static $allowed_actions = array(
    );

    private static $managed_models = array(
        'PC\Product',
        'PC\ProductRequest',
    );

    private static $url_segment = 'pc-product';
    private static $menu_title = 'PC Products';
    public $showImportForm = false;

}

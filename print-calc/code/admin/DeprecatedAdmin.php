<?php

namespace PC;

class DeprecatedAdmin extends \ModelAdmin  {

    private static $allowed_actions = array(
    );

    private static $managed_models = array(
        'PC\Machine',
        'PC\MachineHourRate',
        'PC\MachineType',
        'PC\ClientOrder',
        'PC\DeliveryType',
        'PC\Binding',
        'PC\BindingType',
        'PC\BindingOption',
        'PC\BookColorType',
        'PC\BookFormat',
        'PC\CoverDecoration',
        'PC\CoverDecorationType',
        'PC\CoverType',
        'PC\FileFormat',
        'PC\Job',
        'PC\JobPriority',
        'PC\JobStatus',
        'PC\Order',
        'PC\OrderDeadline',
        'PC\OrderStatus',
        'PC\PrintQuality',
        'PC\User',
    );

    private static $url_segment = 'pc-deprecated';
    private static $menu_title = 'PC Deprecated';
    public $showImportForm = false;

}

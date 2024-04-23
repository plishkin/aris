<?php

namespace PC;

class FlowAdmin extends \ModelAdmin  {

    private static $allowed_actions = array(
    );

    private static $managed_models = array(
        'PC\Flow',
        'PC\FlowItem',
        'PC\FlowOperation',
        'PC\FlowOperationItem',
    );

    private static $url_segment = 'pc-flow';
    private static $menu_title = 'PC Flows';
    public $showImportForm = false;

}

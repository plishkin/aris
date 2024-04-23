<?php

namespace PC;

class TypographyAdmin extends \ModelAdmin  {

    private static $allowed_actions = array(
    );

    private static $managed_models = array(
        'BB\Machine',
        'PC\PaperStockSheet',
        'PC\MachinePaperWeight',
        'PC\PrintQuality',
        'BB\PaperFormat',
        'BB\PaperDensity',
        'BB\PaperType',
        'BB\Chromaticity',
        'BB\Circulation',
        'BB\Finishing',
        'PC\Variable',
    );

    private static $url_segment = 'pc-typography';
    private static $menu_title = 'PC Typography';
    public $showImportForm = false;

}

<?php

/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 19.03.12
 * Time: 15:50
 */

namespace BB;
/**
 * Class PaperDensity
 * @package BB
 * @property string Title
 * @property string Abbr
 * @property string Description
 * @property int Amount
 */
class PaperDensity extends \DataObject implements \BB\Numerable {

    private static $db = array(
        'Title' => 'Varchar(128)',
        'Abbr' => 'Varchar(32)',
        'Description' => 'Text',
        'Amount' => 'Int',
    );

    private static $has_many = array(
        'PaperStockSheets' => 'PC\PaperStockSheet',
        'ProductRequests' => 'PC\ProductRequest',
    );

    private static $belongs_many_many = array(
        'PaperTypes' => 'BB\PaperType',
        'PaperDensityGroups' => 'BB\PaperDensityGroup'
    );

    private static $summary_fields = array(
        'ID' => 'ID',
        'Title' => 'Title',
        'Amount' => 'Amount',
    );

    private static $default_sort = 'Title * 1 ASC';

    private static $singular_name = 'Paper Density';

    private static $plural_name = 'Paper Densities';

    private static $add_action = 'a Paper Density';

    public function onBeforeWrite() {
        if (!$this->Title) $this->Title = $this->Amount;
        parent::onBeforeWrite();
    }

    public function forTemplate() {
        $units = _t("Units.g_d_m2", "g/m<sup>2</sup>");
        return $this->Amount . ($units ? ' ' . $units : '');
    }

}

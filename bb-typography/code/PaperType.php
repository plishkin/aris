<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 19.03.12
 * Time: 16:38
 */

namespace BB;
/**
 * Class PaperType
 * @package BB
 * @property string Title
 * @property string Description
 */
class PaperType extends \DataObject {
    private static $db = array(
        'Title' => 'Varchar(128)',
        'Description' => 'Text',
    );

    private static $many_many = array(
        'PaperDensities' => 'BB\PaperDensity',
    );

    private static $has_many = array(
        'PaperStockSheets' => 'PC\PaperStockSheet',
        'ProductRequests' => 'PC\ProductRequest',
    );

    private static $belongs_many_many = array(
    );

    private static $singular_name = 'Paper Type';

    private static $plural_name = 'Paper Types';

    private static $add_action = 'a Paper Type';

    public function forTemplate() {
        return $this->has_extension('TranslatableDataObject') ? $this->T("Title") : $this->Title;
    }

}

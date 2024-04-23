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
 * @method \ManyManyList|PaperDensity[] PaperDensities()
 */
class PaperDensityGroup extends \DataObject {

    private static $db = array(
        'Title' => 'Varchar(128)',
        'Description' => 'Text',
    );

    private static $many_many = array(
        'PaperDensities' => 'BB\PaperDensity',
    );

    private static $belongs_many_many = array(
        'Machine' => 'PC\Machine',
    );

    private static $summary_fields = array(
        'Title' => 'Title',
    );

    private static $default_sort = 'Title * 1 ASC';

    private static $singular_name = 'Paper Density Group';

    private static $plural_name = 'Paper Density Groups';

    public function onBeforeWrite() {
        parent::onBeforeWrite();
    }

    public function forTemplate() {
        return $this->Title;
    }

}

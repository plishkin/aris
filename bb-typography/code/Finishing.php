<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 20.03.12
 * Time: 15:50
 */

namespace BB;

class Finishing extends \DataObject
{
    private static $db = array(
        'Title' => 'Varchar(128)',
        'Abbr' => 'Varchar(64)',
        'Description' => 'Text',
    );

    private static $belongs_many_many = array(
    );

    private  static $summary_fields = array(
        'Title' => 'Title',
        'Abbr' => 'Abbr',
    );

    private static $singular_name = 'Finishing';

    private static $plural_name = 'Finishings';

    private static $add_action = 'a Finishing';

    public function forTemplate(){
        return $this->has_extension('TranslatableDataObject') ? $this->T("Title") : $this->Title;
    }

}

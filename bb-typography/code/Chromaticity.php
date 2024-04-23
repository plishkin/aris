<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 19.03.12
 * Time: 16:33
 */

namespace BB;
/**
 * Class Chromaticity
 * @package BB
 * @property string Title
 * @property string Description
 * @property int SeparationsCount
 */
class Chromaticity extends \DataObject
{

    private static $db = array(
        'Title' => 'Varchar(128)',
        'Description' => 'Text',
        'SeparationsCount' => 'Int',
    );

    private static $has_many = array(
        'ProductRequests' => 'PC\ProductRequest',
    );

    private static $belongs_many_many = array(
        'Products' => 'PC\Product',
    );

    private static $singular_name = 'Chromaticity';

    private static $plural_name = 'Chromaticities';

    public function forTemplate(){return $this->Title;}

}

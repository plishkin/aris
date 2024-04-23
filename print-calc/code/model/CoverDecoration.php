<?php

namespace PC;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property integer CoverDecorationTypeID 
 * @property integer FlowID 
 * @property bool IsVisible 
 * @property string Name 
 * @property int SortOrder 
 * @method \HasManyList CalculationRequests() 
 * @method CoverDecorationType CoverDecorationType() 
 * @method Flow Flow() 
 */

class CoverDecoration extends \DataObject {
    
    private static $singular_name = "Cover Decoration";
    
    private static $plural_name = "Cover Decorations";
    
    private static $db = array(
        'Name' => 'Varchar',
        'IsVisible' => 'Boolean',
        'SortOrder' => 'Int',
    );

    private static $has_many = array(
        'CalculationRequests' => 'PC\CalculationRequest',
    );

    private static $has_one = array(
        'CoverDecorationType' => 'PC\CoverDecorationType',
    );

    


}
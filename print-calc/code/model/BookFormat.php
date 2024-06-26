<?php

namespace PC;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property string Title
 * @property bool IsVisible
 * @property int SortOrder
 * @method \ManyManyList BindingTypes() 
 * @method \HasManyList CalculationRequests() 
 * @method \BB\PaperFormat PaperFormat()
 */

class BookFormat extends \DataObject {
    
    private static $singular_name = "Book Format";
    
    private static $plural_name = "Book Formats";
    
    private static $belongs_many_many = array(
        'BindingTypes' => 'PC\BindingType',
    );

    private static $db = array(
        'Title' => 'Varchar(128)',
        'IsVisible' => 'Boolean',
        'SortOrder' => 'Int',
    );

    private static $has_many = array(
        'CalculationRequests' => 'PC\CalculationRequest',
    );

    private static $has_one = array(
        'PaperFormat' => 'BB\PaperFormat',
    );




}

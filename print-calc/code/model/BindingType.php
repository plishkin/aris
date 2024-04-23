<?php

namespace PC;
use Utils\FrontEnd;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property string Name 
 * @method \HasManyList Bindings() 
 * @method \ManyManyList BookFormats() 
 * @method \HasManyList CoverDecorationTypes() 
 * @method \ManyManyList CoverTypes() 
 */

class BindingType extends \DataObject {
    
    private static $singular_name = "Binding Type";
    
    private static $plural_name = "Binding Types";
    
    private static $belongs_many_many = array(
        'CoverTypes' => 'PC\CoverType',
    );

    private static $db = array(
        'Name' => 'Varchar(50)',
    );

    private static $has_many = array(
        'Bindings' => 'PC\Binding',
        'CoverDecorationTypes' => 'PC\CoverDecorationType',
    );

    private static $many_many = array(
        'BookFormats' => 'PC\BookFormat',
    );

    

    /* ----- Class content ----- */

	
	public function getCoverDecorationTypeOrdered() {
        return FrontEnd::filter(
            $this->CoverDecorationTypes()->sort('SortOrder ASC')
        );
	}
	
	public function getBookFormatOrdered() {
        return FrontEnd::filter(
            $this->BookFormats()->sort('"PC\BindingType_BookFormats"."SortOrder" ASC')
        );
	}


}

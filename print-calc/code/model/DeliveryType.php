<?php

namespace PC;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property string Name 
 * @property float Price 
 * @method \HasManyList CalculationRequests() 
 */

class DeliveryType extends \DataObject {
    
    private static $singular_name = "Delivery Type";
    
    private static $plural_name = "Delivery Types";
    
    private static $db = array(
        'Name' => 'Varchar(50)',
        'Price' => 'Double',
    );

    private static $has_many = array(
        'CalculationRequests' => 'PC\CalculationRequest',
    );

    


}
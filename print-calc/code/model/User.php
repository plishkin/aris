<?php

namespace PC;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property string Email 
 * @property string Name 
 * @property string Password 
 * @property string Phone
 * @method \HasManyList ClientOrders()
 */

class User extends \DataObject {
    
    private static $singular_name = "User";
    
    private static $plural_name = "Users";
    
    private static $db = array(
        'Name' => 'Varchar(100)',
        'Email' => 'Varchar(100)',
        'Phone' => 'Varchar(100)',
        'Password' => 'Varchar(100)',
    );

    private static $has_many = array(
        'ClientOrders' => 'PC\ClientOrder',
    );

    

    /* ----- Table content ----- */

    
//    public static function getInstance()
//    {
//        return Doctrine_Core::getTable('User');
//    }

}

<?php

namespace PC;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property bool IsOffsetMachine 
 * @property bool IsPrintMachine 
 * @property string Name 
 * @method \HasManyList|Machine[] Machines()
 */

class MachineType extends \DataObject {
    
    private static $singular_name = "Machine Type";
    
    private static $plural_name = "Machine Types";
    
    private static $db = array(
        'Name' => 'Varchar',
    );

    private static $has_many = array(
        'Machines' => 'PC\Machine',
    );

    private static $belongs_many_many = array(
        'FlowOperations' => 'PC\FlowOperation',
    );

    public function getAvailablePropertyIDs($name,$params=null) {
        $arr = array();
        foreach ($this->Machines() as $machine) {
            $arr = array_merge($arr, $machine->getAvailablePropertyIDs($name,$params));
        }
        return $arr;
    }


}

<?php

namespace BB;

/**
 * Class PressMachine
 * @package BB
 */

class OffsetMachine extends PressMachine {
    
    private static $singular_name = "Offset Machine";
    
    private static $plural_name = "Offset Machines";
    
    private static $db = array(
    );

    private static $has_many = array(
    );

    private static $has_one = array(
    );

    private static $many_many = array(
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();



        return $fields;
    }

}











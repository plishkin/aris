<?php

namespace BB;

use Utils\GriedFieldExtraFieldsUtils;

/**
 * Class PressMachine
 * @package BB
 * @property int FormSetupSeconds
 */

class CuttingMachine extends Machine {
    
    private static $singular_name = "Cutting Machine";
    
    private static $plural_name = "Cutting Machines";
    
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











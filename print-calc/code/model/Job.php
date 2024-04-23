<?php

namespace PC;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property integer JobPriorityID 
 * @property integer JobStatusID 
 * @property integer MachineID 
 * @property integer OrderID 
 * @method JobPriority JobPriority() 
 * @method JobStatus JobStatus() 
 * @method Machine Machine() 
 * @method Order Order() 
 */

class Job extends \DataObject {
    
    private static $singular_name = "Job";
    
    private static $plural_name = "Jobs";
    
    private static $has_one = array(
        'Order' => 'PC\Order',
        'Machine' => 'PC\Machine',
        'JobStatus' => 'PC\JobStatus',
        'JobPriority' => 'PC\JobPriority',
    );

    

    /* ----- Table content ----- */

    
//    public static function getInstance()
//    {
//        return Doctrine_Core::getTable('Job');
//    }

}

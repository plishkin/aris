<?php

namespace PC;
use Utils\FrontEnd;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property string Title
 * @property string PriceTrace
 * @property string ErrorMessages
 * @property int FlowItemID
 * @property int FlowOperationID
 * @method FlowItem FlowItem()
 * @method FlowOperation FlowOperation()
 * @method \HasManyList|MachineCalculationItem[] MachineCalculationItems()
 */

class FlowOperationItem extends \DataObject {
    
    private static $singular_name = "Flow Operation Item";
    
    private static $plural_name = "Flow Operation Items";
    
    private static $has_one = array(
        'FlowItem' => 'PC\FlowItem',
        'FlowOperation' => 'PC\FlowOperation',
    );

    private static $has_many = array(
        'MachineCalculationItems' => 'PC\MachineCalculationItem'
    );

    private static $many_many = array(
    );

    private static $belongs_many_many = array(
    );

    private static $db = array(
        'PriceTrace' => 'Text',
        'ErrorMessages' => 'Text',
    );

    private static $summary_fields = array(
        'ID', 'Created', 'FlowOperation.Name', 'FlowItem.ID'
    );

    private static $searchable_fields = array(
        'ID', 'Created',
    );

    private static $default_sort = 'Created DESC';

    public function Name() {
        return $this->FlowOperation()->Name;
    }

    public function getProductRequest() {
        return $this->FlowItem()->ProductRequest();
    }

    public function calculatePrice() {
        if ($this->FlowOperation()->MachineClasses()->count()) {
            return $this->calculatePriceByClasses();
        }
        return $this->calculatePriceByTypes();
    }

    public function calculatePriceByClasses() {
        $pr = $this->getProductRequest();
        $this->log("\r\n".
            '-------------------------------------------------------------'."\r\n".
            '--------------------- NEW PRICE CALCULATION -----------------'."\r\n".
            '-------------------------------------------------------------'."\r\n".
            $pr->getSummary("\r\n")
        );
        /** @var MachineClass $firstClass */
        $firstClass = $this->FlowOperation()->MachineClasses()->first();
        if (!$firstClass || !$firstClass->exists()) {
            $this->logError('No machine classes defined for FlowOperation -> '.$this->FlowOperation()->Name,true);
        }
        /** @var Machine $firstMachine */
        $firstMachine = FrontEnd::getFor($firstClass->MachineClass)->first();
        if (!$firstMachine || !$firstMachine->exists()) {
            $this->logError('No machine defined for MachineType -> '.$firstClass->Name,true);
        }
        $this->write();

        $lowestPrice = 0;
        foreach ($this->FlowOperation()->MachineClasses() as $class) {
            $this->log('------------ Machine Type -> '.$class->Name);
            /** @var \BB\Machine $machine */
            foreach (FrontEnd::getFor($class->MachineClass) as $machine) {
                $this->log('------------ Machine -> '.$machine->Name);
                $price = $machine->calculateProductRequestPrice($pr,$this);
                if (!$price || $price<0) {
                    $this->logError('Wrong lowest price for machine -> '.$firstMachine->Name,false);
                } else {
                    $lowestPrice = $price < $lowestPrice || !$lowestPrice ? $price : $lowestPrice;
                }
            }
        }
        $this->write();
        return $lowestPrice;
    }

    public function calculatePriceByTypes() {
        $pr = $this->getProductRequest();
        $this->log("\r\n".
            '-------------------------------------------------------------'."\r\n".
            '--------------------- NEW PRICE CALCULATION -----------------'."\r\n".
            '-------------------------------------------------------------'."\r\n".
            $pr->getSummary("\r\n")
        );
        /** @var MachineType $firstType */
        $firstType = $this->FlowOperation()->MachineTypes()->first();
        if (!$firstType || !$firstType->exists()) {
            $this->logError('No machine types defined for FlowOperation -> '.$this->FlowOperation()->Name,true);
        }
        /** @var Machine $firstMachine */
        $firstMachine = $firstType->Machines()->first();
        if (!$firstMachine || !$firstMachine->exists()) {
            $this->logError('No machine defined for MachineType -> '.$firstType->Name,true);
        }

        $lowestPrice = 0;
        foreach ($this->FlowOperation()->MachineTypes() as $type) {
            $this->log('------------ Machine Type -> '.$type->Name);
            foreach ($type->Machines() as $machine) {
                $this->log('------------ Machine -> '.$machine->Name);
                $price = $machine->calculateProductRequestPrice($pr,$this);
                if (!$price || $price<0) {
                    $this->logError('Wrong lowest price for machine -> '.$firstMachine->Name,false);
                } else {
                    $lowestPrice = $price < $lowestPrice || !$lowestPrice ? $price : $lowestPrice;
                }
            }
        }
        $this->write();
        return $lowestPrice;
    }

    private function log($message){
        $this->PriceTrace = $this->PriceTrace . "\r\n" . $message;
    }

    private function logError($message, $strict = false){
        $this->ErrorMessages = $this->ErrorMessages . "\r\n" . $message;
        if ($strict) {
            $this->write();
            throw new \Exception($message);
        }
    }



        

}

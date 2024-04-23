<?php

namespace PC;
use BB\Chromaticity;
use BB\PaperDensity;
use BB\PaperFormat;
use BB\PaperType;
use Utils\FrontEnd;

/** 
 * This class has been auto-generated by the SS importer module
 * 
 * @property string IP
 * @property int Circulation
 * @property int PagesCount
 * @property int PaperTypeID
 * @property int PaperDensityID
 * @property int PaperFormatID
 * @property int ChromaticityID
 * @property int ProductID
 * @property int FlowItemID
 * @property int ProductRequestConfigID
 * @property int MemberID
 * @property float Price
 * @method PaperFormat PaperFormat()
 * @method PaperType PaperType()
 * @method PaperDensity PaperDensity()
 * @method Chromaticity Chromaticity()
 * @method Product Product()
 * @method FlowItem FlowItem()
 * @method \Member Member()
 * @method ProductRequestConfig ProductRequestConfig()
 * @method \HasManyList|MachineCalculationItem[] MachineCalculationItems()
 */

class ProductRequest extends \DataObject {
    
    private static $singular_name = "Product Request";
    
    private static $plural_name = "Product Requests";
    
    private static $db = array(
        'Circulation' => 'Int',
        'PagesCount' => 'Int',
        'Price' => 'Double',
        'IP' => 'Varchar',
    );

    private static $has_many = array(
        'MachineCalculationItems' => 'PC\MachineCalculationItem'
    );

    private static $has_one = array(
        'Product' => 'PC\Product',
        'ProductRequestConfig' => 'PC\ProductRequestConfig',
        'PaperType' => 'BB\PaperType',
        'PaperDensity' => 'BB\PaperDensity',
        'PaperFormat' => 'BB\PaperFormat',
        'Chromaticity' => 'BB\Chromaticity',
        'FlowItem' => 'PC\FlowItem',

        'Member' => 'Member',
        'User' => 'PC\User',
    );

    private static $many_many = array(
    );

    private static $summary_fields = array(
        'Created',
        'Price',
        'Summary',
        'IP',
    );

    private static $default_sort = 'Created DESC';


    protected function onBeforeWrite() {
        $this->ProductID = $this->ProductRequestConfig()->ProductID;
        if (!$this->IP) $this->IP = $_SERVER['REMOTE_ADDR'];
        parent::onBeforeWrite();
    }

    protected function onAfterDelete() {
        if ($this->FlowItemID) FlowItem::get()->byID($this->FlowItemID)->delete();
        parent::onAfterDelete();
    }

    public function getSummary($separator = ', ') {
        return implode($separator, array(
            $this->fieldLabel('Product').': '.$this->Product()->Title,
            $this->fieldLabel('PaperType').': '.$this->PaperType()->Title,
            $this->fieldLabel('PaperDensity').': '.$this->PaperDensity()->Title,
            $this->fieldLabel('Chromaticity').': '.$this->Chromaticity()->Title,
            $this->fieldLabel('PagesCount').': '.$this->PagesCount,
            $this->fieldLabel('Circulation').': '.$this->Circulation,
        ));
    }

    public function getFormatWithBleeds($source_format = null) {
        return $this->Product()->getFormatWithBleeds(
            $source_format ? $source_format : $this->PaperFormat()
        );
    }

    public function caclulatePrice() {
        $flowItem = $this->FlowItem();
        if (!$flowItem || !$flowItem->exists()) {
            $flowItem = new FlowItem();
            $flowItem->ProductRequestID = $this->ID;
            $flowItem->ProductID = $flowItem->ProductRequest()->ProductID;
            $flowItem->FlowID = $flowItem->Product()->FlowID;
            $flowItem->write();
            $this->FlowItemID = $flowItem->ID;
            $this->write();
        }
        return $flowItem->calculatePrice();
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->insertBefore(
            \ReadonlyField::create('CreatedReadonly', $this->fieldLabel('Created'),$this->Created),
            'Circulation'
        );

        return $fields;
    }


    public static function getFrontEndFieldNames() {
        return
            array(
                'PaperFormatID',
                'ChromaticityID',
                'PaperDensityID',
                'PaperTypeID',
                'Circulation',
                'PagesCount',
            );
    }

    /**
     * @param string $name
     * @param array $params
     * @return \DropdownField
     */
    public function getFrontendField($name, $params=array()) {
        $params = array_merge(array(
            'product' => $this->Product(),
            'product_request' => $this,
        ),$params);
        $method = 'getFrontend'.$name.'Field';
        $field = method_exists($this,$method) ? $this->{$method}($params) : null;
        if (!$field) {
            if (in_array($name,array('Circulation','PagesCount'))) {
                $field = new \NumericField($name, $this->fieldLabel($name), $this->{$name});
            }
            $map = array(
                'PaperTypeID'=>array('PaperType','PaperTypes'),
                'ChromaticityID'=>array('Chromaticity','Chromaticities'),
                'PaperDensityID'=>array('PaperDensity','PaperDensities'),
                'PaperFormatID'=>array('PaperFormat','PaperFormats'),
            );
            if (isset($map[$name])) {
                $field = $this->getFrontendDropdownField($name,$map[$name][0],$map[$name][1],$params);
            }
        }
        return $field;
    }

    public function getFrontendDropdownField($name, $singular, $plural, $params=null) {
        $list = $this->Product()->getAvailable($this->Product()->{$plural}(), $singular, $params);
        return $list ?
            new \DropdownField($name,$this->fieldLabel($singular),$list->map()->toArray(), $this->{$name})
            : null;
    }

//    public function getFrontendPaperFormatIDField($params = null) {
//        $params['PaperFormats'] = $this->Product()->PaperFormats();
//        $list = $this->Product()->getAvailable($this->Product()->PaperFormats(), 'PaperFormat', $params);
//        return new \DropdownField(
//            'PaperFormatID', $this->fieldLabel('PaperFormat'),
//            $formats, $this->PaperFormatID
//        );
//
//
//
//
//        $formats = array();
//        $machineFormatIDs = $this->Product()->getAvalableIDs('PaperFormat', $params);
//        foreach ($this->Product()->PaperFormats() as $format) {
//            /** @var PaperFormat $machineFormat */
//            foreach (PaperFormat::get()->byIDs($machineFormatIDs) as $machineFormat) {
//                if ($machineFormat->getQuantityFormatSpreadedOut($this->getFormatWithBleeds($format))>0) {
//                    $formats[$format->ID] = $format->Title;
//                }
//            }
//        }
//        return new \DropdownField(
//            'PaperFormatID', $this->fieldLabel('PaperFormat'),
//            $formats, $this->PaperFormatID
//        );
//    }





}

<?php

namespace PC;
use BB\Chromaticity;
use BB\PaperDensity;
use BB\PaperFormat;
use BB\PaperType;

/**
 * Class Product
 * @package BB
 * @property string Title
 * @property bool IsActive
 * @property int MaxAllowedPages
 * @property int QuantityPerPack
 * @property float SimmetricalBleed
 * @property int FlowID
 * @method \ManyManyList|PaperFormat[] PaperFormats()
 * @method \ManyManyList|PaperDensity[] PaperDensities()
 * @method \ManyManyList|PaperType[] PaperTypes()
 * @method \ManyManyList|Chromaticity[] Chromaticities()
 * @method \HasManyList|FlowItem[] FlowItems()
 * @method Flow Flow()
 */
class Product extends \DataObject {

    private static $db = array(
        'Title' => 'Varchar(128)',
        'IsActive' => 'Boolean(0)',
        'MaxAllowedPages' => 'Int(0)',
        'QuantityPerPack' => 'Int',
        'SimmetricalBleed' => 'Float',
    );

    private static $many_many = array(
        'SubProducts' => 'PC\Product',
        'PaperFormats' => 'BB\PaperFormat',
        'Chromaticities' => 'BB\Chromaticity',
        'PaperDensities' => 'BB\PaperDensity',
        'PaperTypes' => 'BB\PaperType',
    );

    private static $belongs_many_many = array(
        'Products' => 'PC\Product',
    );

    private static $has_many = array(
        'FlowItems' => 'PC\FlowItem',
    );

    private static $has_one = array(
        'Flow' => 'PC\Flow'
    );

    private static $summary_fields = array(
        'Title',
        'IsActive',
    );

    private static $singular_name = 'Product';

    private static $plural_name = 'Products';

    public function forTemplate(){
        return $this->has_extension('TranslatableDataObject') ? $this->T("Title") : $this->Title;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
//        $fields->add($this->getTranslatableTabSet());
        return $fields;
    }

    public function getFormatWithBleeds(PaperFormat $source_format) {
        $format = new PaperFormat();
        $format->Width = $source_format->Width + (2 * $this->SimmetricalBleed);
        $format->Height = $source_format->Height + (2 * $this->SimmetricalBleed);
        return $format;
    }

    public function getAvailable(\DataList $datalist, $name, $params = null) {
        $arr = $this->getAvalableIDs($name, $params);
        return $arr ? $datalist->byIDs($arr) : null;
    }

    public function getAvalableIDs($name,$params=null){
        $arr = array();
        foreach ($this->Flow()->FlowOperations() as $operation) {
            $arr = array_merge($arr,$operation->getAvailablePropertyIDs($name,$params));
//            \Debug::dump($arr);
        }
        return $arr;
    }


}

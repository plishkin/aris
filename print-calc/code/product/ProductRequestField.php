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
 * @property string Name
 * @property bool IsActive
 * @property bool IsRequired
 * @property int ProductRequestConfigID
 * @method ProductRequestConfig ProductRequestConfig()
 */
class ProductRequestField extends \DataObject {

    private static $db = array(
        'Title' => 'Varchar(128)',
        'IsActive' => 'Boolean(0)',
        'IsRequired' => 'Boolean(0)',
        'Name' => 'Varchar(128)',
        'SortOrder' => 'Int',
    );

    private static $many_many = array(
    );

    private static $belongs_many_many = array(
    );

    private static $has_many = array(
    );

    private static $has_one = array(
        'ProductRequestConfig' => 'PC\ProductRequestConfig',
    );

    public static $default_sort='SortOrder';

    private  static $summary_fields = array(
        'Title' ,
        'IsActive',
        'IsRequired' ,
        'Name' ,
    );

    private static $singular_name = 'Product Request Field';

    private static $plural_name = 'Product Property Fields';

    protected function onBeforeWrite() {
        if (!$this->Title) $this->Title = $this->Name;
        parent::onBeforeWrite();
    }


    public function forTemplate(){
        return $this->has_extension('TranslatableDataObject') ? $this->T("Title") : $this->Title;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->replaceField('Name',new \ReadonlyField('Name','Name',$this->Name));

        return $fields;
    }




}

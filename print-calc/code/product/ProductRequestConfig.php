<?php

namespace PC;
use BB\Chromaticity;
use BB\PaperDensity;
use BB\PaperFormat;
use BB\PaperType;
use Utils\GriedFieldExtraFieldsUtils;

/**
 * Class Product
 * @package BB
 * @property string Title
 * @property bool IsActive
 * @property int ProductID
 * @method Product Product()
 * @method \HasManyList|ProductRequestField[] ProductRequestFields()
 */
class ProductRequestConfig extends \DataObject {

    private static $db = array(
        'Title' => 'Varchar(128)',
        'Description' => 'Varchar(128)',
        'IsActive' => 'Boolean(0)',
    );

    private static $many_many = array(
    );

    private static $belongs_many_many = array(
        'PrintCalcPages' => 'PrintCalcPage'
    );

    private static $has_many = array(
        'ProductRequestFields' => 'PC\ProductRequestField',
    );

    private static $has_one = array(
        'Product' => 'PC\Product'
    );

    private  static $summary_fields = array(
        'Title', 'Description', 'IsActive',
    );

    private static $singular_name = 'Product Request Config';

    private static $plural_name = 'Product Request Configs';

    public function forTemplate(){
        return $this->has_extension('TranslatableDataObject') ? $this->T("Title") : $this->Title;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        if ($this->ID && $this->ProductID) {
            $allowed_fields = ProductRequest::getFrontEndFieldNames();
            foreach ($this->ProductRequestFields()->exclude('Name',$allowed_fields) as $prField) {
                $prField->delete();
            }
            foreach ($allowed_fields as $name) {
                $prField = $this->ProductRequestFields()->filter('Name',$name)->first();
                if (!$prField || !$prField->exists()) {
                    $prField = new ProductRequestField();
                    $prField->Name = $name;
                    $prField->ProductRequestConfigID = $this->ID;
                    $prField->write();
                }
            }
            /** @var \GridFieldConfig $cfg */
            $cfg = \GridFieldConfig::create()
                ->addComponent(new \GridFieldDetailForm())
                ->addComponent(new \GridFieldButtonRow())
                ->addComponent(new \GridFieldToolbarHeader())
                ->addComponent(new \GridFieldTitleHeader())
                ->addComponent(new \GridFieldEditableColumns())
                ->addComponent(new \GridFieldSortableRows('SortOrder'))
            ;
            /** @var \GridField $grid */
            $grid = \GridField::create(
                'ProductRequestFields',
                $this->fieldLabel('ProductRequestFields'),
                $this->ProductRequestFields(),
                $cfg
            );
            $cfg->getComponentByType('GridFieldEditableColumns')->setDisplayFields(array(
                'Title'  => array(
                    'title' => $this->fieldLabel('Title'),
                    'field' => 'TextField'
                ),
                'IsActive'  => array(
                    'title' => $this->fieldLabel('IsActive'),
                    'field' => 'CheckboxField'
                ),
                'IsRequired'  => array(
                    'title' => $this->fieldLabel('IsRequired'),
                    'field' => 'CheckboxField'
                ),
                'Name' => array(
                    'title' => $this->fieldLabel('Name'),
                    'field' => 'ReadonlyField'
                )
            ));
            $fields->replaceField('ProductRequestFields',$grid);

        } else {
            $fields->removeByName('ProductRequestFields');
        }

        return $fields;
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: slavka
 * Date: 16.05.15
 * Time: 13:07
 */

namespace Utils;


class GriedFieldExtraFieldsUtils extends \Object {

    /** @var \DataObject */
    private $dataObject;

    /** @var \FieldList */
    private $fields;

    function __construct(\DataObject $dataObject=null, \FieldList $fields=null) {
        $this->dataObject = $dataObject;
        if (!$fields) {
            $tabbedFields = $dataObject->scaffoldFormFields(array(
                // Don't allow has_many/many_many relationship editing before the record is first saved
                'includeRelations' => ($dataObject->ID > 0),
                'tabbed' => true,
                'ajaxSafe' => true
            ));
            $dataObject->extend('updateCMSFields', $tabbedFields);
            $fields = $tabbedFields;
        }
        $this->fields = $fields;
    }

    public function getCMSFields() {
        $dataObject = $this->dataObject;
        if (!$dataObject) return \FieldList::create();
        $fields = $this->fields;

        $extraManyMany = $dataObject->config()->get('many_many_extraFields');
        if (!$extraManyMany) return $fields;

        foreach ($extraManyMany as $gridName => $extraFields) {
            $grid = $this->getExtraGridField($gridName);
            $class = $grid->getModelClass();
            $do = $class::create();
            $cfg = $grid->getConfig();
            /** @var \GridFieldAddNewInlineButton $AddNewInlineButton */
            $AddNewInlineButton = $cfg->getComponentByType('GridFieldAddNewInlineButton');
            if ($AddNewInlineButton) {
                $AddNewInlineButton->setTitle(
                    _t('CMSMain.AddNewButton','Add new').' '.$class::create()->i18n_singular_name()
                );
            }
            /** @var \GridFieldEditableColumns $comp */
            $comp = $cfg->getComponentByType('GridFieldEditableColumns');
            if ($comp) {
                $field_names = array_keys(array_merge($do->summaryFields(),$extraFields));
                $arr = array();
                foreach ($field_names as $name) {
                    $title = $dataObject->fieldLabel($name);
                    $arr[$name] = $title;
                    if (in_array($name,$extraFields)) {
                        $arr[$name] = array(
                            'title' => $title,
                            'callback' => function(\DataObject $record, $column, $grid) {
                                return new \TextField(
                                    $column,
                                    $record->fieldLabel($column),
                                    $record->{$column}
                                );
                            }
                        );
                    }
                }
                $comp->setDisplayFields($arr);
            }
            $fields->replaceField($gridName,$grid);
        }

        return $fields;
    }

    public function getExtraGridField($name) {
        /** @var \GridField $grid */
        $grid = \GridField::create(
            $name,
            $this->dataObject->fieldLabel($name),
            $this->dataObject->{$name}(),
            static::getDefaultGridFieldConfig()
        );
        return $grid;
    }

    protected static function getDefaultGridFieldConfig(){
        /** @var \GridFieldConfig $cfg */
        $cfg = \GridFieldConfig::create()
            ->addComponent(new \GridFieldDetailForm())
            ->addComponent(new \GridFieldButtonRow())
//            ->addComponent($GridFieldAddExistingSearchButton = new \GridFieldAddExistingSearchButton('buttons-before-right'))
            ->addComponent(new \GridFieldAddExistingAutocompleter('buttons-before-right'))
            ->addComponent(new \GridFieldToolbarHeader())
            ->addComponent(new \GridFieldTitleHeader())
            ->addComponent(new \GridFieldEditableColumns())
            ->addComponent(new \GridFieldEditButton())
            ->addComponent(new \GridFieldDeleteAction('unlinkrelation'))
            ->addComponent(new \GridFieldDeleteAction())
            ->addComponent(new \GridFieldAddNewInlineButton());
//        $GridFieldAddExistingSearchButton->setTitle(
//            _t('GridField.RelationSearch','Relation search')
//        );
        return $cfg;
    }

    /**
     * @return \DataObject
     */
    public function getDataObject() {
        return $this->dataObject;
    }

    /**
     * @param \DataObject $dataObject
     */
    public function setDataObject($dataObject) {
        $this->dataObject = $dataObject;
    }

    /**
     * @return \FieldList
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @param \FieldList $fields
     */
    public function setFields($fields) {
        $this->fields = $fields;
    }



}

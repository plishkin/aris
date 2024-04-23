<?php
/**
 * Created by PhpStorm.
 * User: slavka
 * Date: 31.05.15
 * Time: 9:30
 */

namespace PC;


use BB\PaperFormat;
use FieldList;
use Utils\FrontEnd;

class ProductRequestForm extends \Form {

    /** @var ProductRequest */
    private $product_request = null;
    /** @var \SS_List */
    private $product_request_configs = null;

    /**
     * @param \Controller $controller
     * @param ProductRequest $product_request
     * @param ProductRequestConfig[] $product_request_configs
     * @param string $name
     * @param FieldList $actions
     * @param \RequiredFields $validator
     */
    public function __construct(
            $controller,
            ProductRequest $product_request,
            $product_request_configs,
            $name=null,
            FieldList $actions,
            $validator=null ) {

        $this->product_request = $product_request;
        $this->product_request_configs = $product_request_configs;
        $name = $name ? $name : str_replace('\\','-',get_called_class());
        $validator = $this->getRequiredFields();
        parent::__construct($controller, $name, $this->getFields(), $actions, $validator);
    }

    public function getRequiredFields() {
        return new \RequiredFields(
            FrontEnd::filter(
                $this->product_request
                    ->ProductRequestConfig()
                    ->ProductRequestFields()
                    ->filter('IsRequired',1)
            )->map('Name','Name')->toArray()
        );
    }

    public function getFields() {
        $product_request = $this->product_request;
        $fields = new \FieldList();
        if (!$product_request) return $fields;
        if (!$this->product_request_configs || !$this->product_request_configs->count()) {
            return $fields;
        }

        if (!$product_request->ProductRequestConfigID) {
            $product_request->ProductRequestConfigID = $this->product_request_configs->first()->ID;
        }

        $product_request->ProductID = $product_request->ProductRequestConfig()->ProductID;

        $fields->unshift(new \DropdownField(
            'ProductRequestConfigID',
            $product_request->fieldLabel('ProductRequestConfig'),
            $this->product_request_configs->map()->toArray(),
            $product_request->ProductRequestConfigID
        ));

        /** @var ProductRequestField[] $productRequestFields */
        $productRequestFields = FrontEnd::filter(
            $product_request->ProductRequestConfig()->ProductRequestFields()
        );

        $params = array();
        foreach ($productRequestFields as $prField) {
            $realField = $this->product_request->getFrontendField($prField->Name,$params);
            if ($realField instanceof \DropdownField) {
                $params[$realField->getName()] = $realField->getSource();
            }
            if ($realField) $fields->push($realField);
        }

        return $fields;
    }



}
<?php
/**
 * Class PrintCalcPage
 * @method \ManyManyList|PC\ProductRequestConfig[]
 */
class PrintCalcPage extends Page {

    private static $db = array(
    );

    private static $many_many = array(
        'ProductRequestConfigs' => 'PC\ProductRequestConfig',
    );

    private static $has_one = array(
    );

    public static $many_many_extraFields=array(
        'ProductRequestConfigs'=>array(
            'ConfigSortOrder'=>'Int'
        )
    );

    public function ProductRequestConfigs() {
        return $this->getManyManyComponents('ProductRequestConfigs')->sort('ConfigSortOrder');
    }

    function getCMSFields() {
        $fields = parent::getCMSFields();

        $CalcOptionsTab = $fields->findOrMakeTab('Root.CalcOptions');
        $CalcOptionsTab->push(
            new GridField(
                'ProductRequestConfigs',
                $this->fieldLabel('ProductRequestConfigs'),
                $this->getManyManyComponents('ProductRequestConfigs'),
                GridFieldConfig_RelationEditor::create()
                    ->addComponent(new \GridFieldSortableRows('ConfigSortOrder'))
            )
        );

        return $fields;
    }

}

class PrintCalcPage_Controller extends Page_Controller {

    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = array(
        'CalculationRequestForm',
        'reloadForm',
    );

    public function init() {
        parent::init();
        Requirements::javascript(PRINT_CALC_FOLDER.'/js/jquery-2.1.4.min.js');
        Requirements::javascript(PRINT_CALC_FOLDER.'/js/print-calc.js');
    }

    private $product_request = null;

    public function reloadForm(SS_HTTPRequest $request) {
        $arr = array();
        $this->product_request = new \PC\CalculationRequest();
        $this->product_request->update($request->requestVars());
//        Debug::dump($request->requestVars());
        $arr['form'] = $this->CalculationRequestForm()->forTemplate()->forTemplate();
        return json_encode($arr);
    }


    public function CalculationRequestForm() {
        $pr = $this->product_request ? $this->product_request : new \PC\ProductRequest();
        if (!$pr->ProductRequestConfigID) {
            $pr->ProductRequestConfigID = $this->data()->ProductRequestConfigs()->first()->ID;
        }

        $actions = new FieldList(
            FormAction::create("doSayHello")->setTitle("Say hello")
        );

        $required = new RequiredFields('Name');

        $form = new \PC\ProductRequestForm(
            $this,
            $pr,
            $this->data()->ProductRequestConfigs(),
            'CalculationRequestForm',
            $actions,
            $required
        );

        return $form;
    }

    public function CalculationRequestForm1() {

        $cr = $this->product_request ? $this->product_request : new \PC\CalculationRequest();

        $bindingList = \Utils\FrontEnd::filter(PC\Binding::get());
        /** @var PC\Binding $bindingSelected */
        $bindingSelected = $cr->BindingID ? $cr->Binding() : $bindingList->first();

        $bindingDD = new \DropdownField(
            'BindingID',
            $bindingSelected->i18n_singular_name(),
            $bindingList->map()->toArray(),
            $bindingSelected->ID
        );

        $bindingOptionList = \Utils\FrontEnd::filter($bindingSelected->BindingOptions());
        /** @var PC\Binding $bindingOptionSelected */
        $bindingOptionSelected = $bindingOptionList->first();
        if ($candidate = $bindingOptionList->byID($cr->BindingOptionID)) {
            $bindingOptionSelected = $candidate;
        }
//        Debug::dump($cr->BindingOptionID);
        $bindingOptionDD = new \DropdownField(
            'BindingOptionID',
            $bindingOptionSelected->i18n_singular_name(),
            $bindingOptionList->map()->toArray(),
            $bindingOptionSelected->ID
        );


        $bookColorTypeList = \Utils\FrontEnd::filter(PC\BookColorType::get());
        /** @var PC\BookColorType $bookColorTypeSelected */
        $bookColorTypeSelected = $bookColorTypeList->first();
        if ($candidate = $bookColorTypeList->byID($cr->BookColorTypeID)) {
            $bookColorTypeSelected = $candidate;
        }

        $bookColorTypeDD = new \DropdownField(
            'BookColorTypeID',
            $bookColorTypeSelected->i18n_singular_name(),
            $bookColorTypeList->map()->toArray(),
            $bookColorTypeSelected->ID
        );


        $coverDecorationTypeList = \Utils\FrontEnd::filter($bindingSelected->BindingType()->CoverDecorationTypes());
        /** @var PC\CoverDecorationType $coverDecorationTypeSelected */
        $coverDecorationTypeSelected = $coverDecorationTypeList->first();
        if ($candidate = $coverDecorationTypeList->byID($cr->CoverDecorationTypeID)) {
            $coverDecorationTypeSelected = $candidate;
        }

        $coverDecorationTypeDD = new \DropdownField(
            'CoverDecorationTypeID',
            $coverDecorationTypeSelected->i18n_singular_name(),
            $coverDecorationTypeList->map()->toArray(),
            $coverDecorationTypeSelected->ID
        );


        $coverDecorationList = \Utils\FrontEnd::filter($coverDecorationTypeSelected->CoverDecorations());
        /** @var PC\CoverDecoration $coverDecorationSelected */
        $coverDecorationSelected = $bookColorTypeList->first();
        if ($candidate = $coverDecorationList->byID($cr->CoverDecorationID)) {
            $coverDecorationSelected = $candidate;
        }

        $coverDecorationDD = new \DropdownField(
            'CoverDecorationID',
            $coverDecorationSelected->i18n_singular_name(),
            $coverDecorationList->map()->toArray(),
            $coverDecorationSelected->ID
        );

        $coverPaperTypes = array();
        $coverPaperDensities = array();
        /** @var \PC\PaperStockSheet $pss */
        foreach (\Utils\FrontEnd::filter(PC\PaperStockSheet::getPaperForCover()) as $pss) {
            $coverPaperTypes[$pss->PaperTypeID] = $pss->PaperType()->Title;
            $coverPaperDensities[$pss->PaperDensityID] = $pss->PaperDensity()->Title;
        }

        $bookPaperTypes = array();
        $bookPaperDensities = array();
        /** @var \PC\PaperStockSheet $pss */
        foreach (\Utils\FrontEnd::filter(PC\PaperStockSheet::getPaperForBlock()) as $pss) {
            $bookPaperTypes[$pss->PaperTypeID] = $pss->PaperType()->Title;
            $bookPaperDensities[$pss->PaperDensityID] = $pss->PaperDensity()->Title;
            if (!$pss->PaperTypeID) {
                Debug::dump($pss);die();
            }
        }

        $colorPaperTypes = array();
        $colorPaperDensities = array();
        /** @var \PC\PaperStockSheet $pss */
        foreach (\Utils\FrontEnd::filter(PC\PaperStockSheet::getPaperForBlock(true)) as $pss) {
            $colorPaperTypes[$pss->PaperTypeID] = $pss->PaperType()->Title;
            $colorPaperDensities[$pss->PaperDensityID] = $pss->PaperDensity()->Title;
        }


        $fieldOrder = array(
            'Binding'=>$bindingDD,
            'PrintQuality'=>null,
            'BookColorType'=>$bookColorTypeDD,
            'BindingOption'=>$bindingOptionDD,
            'BookFormat'=>null,
            'CoverPaperType' => new DropdownField(
                'CoverPaperTypeID',
                $cr->fieldLabel('CoverPaperType'),
                $coverPaperTypes,
                $cr->CoverPaperTypeID
            ),
            'CoverPaperDensity' => new DropdownField(
                'CoverPaperDensityID',
                $cr->fieldLabel('CoverPaperDensity'),
                $coverPaperDensities,
                $cr->CoverPaperDensityID
            ),
//            'CoverPaperStockSheet'=>new DropdownField(
//                'CoverPaperStockSheetID',$cr->fieldLabel('CoverPaperStockSheet'),
//                \Utils\FrontEnd::filter(PC\PaperStockSheet::getPaperForCover())->map()->toArray(),
//                $cr->CoverPaperStockSheetID
//            ),
            'BookPaperType' => new DropdownField(
                'BookPaperTypeID',
                $cr->fieldLabel('BookPaperType'),
                $bookPaperTypes,
                $cr->BookPaperTypeID
            ),
            'BookPaperDensity' => new DropdownField(
                'BookPaperDensityID',
                $cr->fieldLabel('BookPaperDensity'),
                $bookPaperDensities,
                $cr->BookPaperDensityID
            ),
//            'BookPaperStockSheet'=>new DropdownField(
//                'BookPaperStockSheetID',$cr->fieldLabel('BookPaperStockSheet'),
//                \Utils\FrontEnd::filter(
//                    PC\PaperStockSheet::getPaperForBlock($bookColorTypeSelected->HasColorPages)
//                )->map()->toArray(),
//                $cr->BookPaperStockSheetID
//            ),
            'ColorPaperType' => new DropdownField(
                'ColorPaperTypeID',
                $cr->fieldLabel('ColorPaperType'),
                $bookPaperTypes,
                $cr->ColorPaperTypeID
            ),
            'ColorPaperDensity' => new DropdownField(
                'ColorPaperDensityID',
                $cr->fieldLabel('ColorPaperDensity'),
                $bookPaperDensities,
                $cr->ColorPaperDensityID
            ),
            'BwPageNumber'=>null,
            'ColorPageNumber'=>null,
            'BookNumber1'=>null,
            'BookNumber2'=>null,
            'BookNumber3'=>null,
            'CoverType'=>null,
            'CoverDecorationType'=>$coverDecorationTypeDD,
            'CoverDecoration'=>$coverDecorationDD,
            'UserName'=>null,
            'UserPhone'=>null,
            'UserEmail'=>null,
            'CompanyName'=>null,
            'DeliveryType'=>null,
            'FileFormat'=>null,
            'AdditionalServices'=> new CheckboxSetField(
                'AdditionalServices',
                $cr->fieldLabel('AdditionalServices'),
                \Utils\FrontEnd::filter(PC\AdditionalService::get()),
                $cr->AdditionalServices()
            ),
        );
        $fields = new FieldList();
        foreach ($fieldOrder as $name => $field) {
            if (!$field) {
                $field = self::scaffoldFormField($cr,$name);
            }
            if ($field) $fields->push($field);
        }
        /** @var DropdownField $dd */
        $dd = $fields->fieldByName('CoverTypeID');

        $actions = new FieldList(
            FormAction::create("doSayHello")->setTitle("Say hello")
        );

        $required = new RequiredFields('Name');

        $form = new Form($this, 'CalculationRequestForm', $fields, $actions, $required);

        return $form;
    }

    public function doSayHello($data, Form $form) {
        $pr = new \PC\ProductRequest();
        $form->saveInto($pr);
        if (Member::currentUserID()) $pr->MemberID = Member::currentUserID();
        $pr->IP = $_SERVER['REMOTE_ADDR'];
        try {
            $pr->write();
            $pr->Price = $pr->caclulatePrice();
            if (!$pr->Price) {
                $form->addErrorMessage('Price','Price is Zero','bad');
                return $this->redirectBack();
            }
            $pr->write();
        } catch (Exception $e) {
            $form->addErrorMessage('Price',$e->getMessage(),'bad');
            return $this->redirectBack();
        }
        $pr->write();

        /** @var PrintCalcResultPage $page */
        $page = PrintCalcResultPage::get()->first();

        return $this->redirect($page->Link('calcrequest').'/'.$pr->ID);
    }

    public static function scaffoldFormField($do, $name) {
        /** @var \FormField $field */
        $field = null;
        if ($do->hasDatabaseField($name)) {
            $field = $do->obj($name)->scaffoldFormField();
            $field->setValue($do->{$name});
        }
        if ($class = $do->has_one($name)) $field = self::hasOneField($do,$name);

        if ($field) {
            $field->setTitle($do->fieldLabel($name));
        }
        return $field;
    }

    public static function hasOneField(DataObject $do,$name,$class=null){
        $title = $class ? $class::create()->i18n_singular_name() : $name;
        if (!$class) {
            $class = $do->has_one($name);
            $title = $do->fieldLabel($name);
        }

        return class_exists($class) ?
            new \DropdownField(
                $name.'ID',
                $title,
                \Utils\FrontEnd::filter($class::get())->map()->toArray(),
                $do->{$name.'ID'}
            ) : null;
    }

}















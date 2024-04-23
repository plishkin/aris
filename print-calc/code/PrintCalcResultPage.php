<?php

class PrintCalcResultPage extends Page {

    private static $db = array(
        'Emails' => 'Text'
    );

    private static $many_many = array(
    );

    function getCMSFields() {
        $fields = parent::getCMSFields();

        return $fields;
    }

}

class PrintCalcResultPage_Controller extends Page_Controller {

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
        'calcrequest',
        'thnx',
        'ClientOrderForm',
    );

    public function init() {
        parent::init();
        Requirements::javascript(PRINT_CALC_FOLDER.'/js/jquery-2.1.4.min.js');
        Requirements::javascript(PRINT_CALC_FOLDER.'/js/print-calc.js');
    }

    /** @var \PC\ProductRequest */
    protected $requestedProductRequest = null;

    public function calcrequest(SS_HTTPRequest $request) {
        $pr = $this->getRequestedProductRequest(true);
        $this->requestedProductRequest = $pr;
        return $this->customise(new ArrayData(array(
            'ProductRequest' => $pr,
        )));
    }

    protected function getRequestedProductRequest($allow_redirects=false){
        $request = $this->getRequest();
        /** @var \PC\ProductRequest $pr */
        $pr = \PC\ProductRequest::get()->byID((int) $request->param('ID'));
        if (!$pr) {
            $pr = \PC\ProductRequest::get()->byID((int) $request->requestVar('ProductRequestID'));
        }
        if ($allow_redirects) {
            if (!$pr) return $this->httpError(404);
//        if (!$pr->canView()) return \Security::permissionFailure();
        }
        return $pr;
    }

    public function FieldsForView(){
        /** @var \PC\ProductRequest $pr */
        $pr = \PC\ProductRequest::get()->byID((int) $this->getRequest()->param('ID'));
        if (!$pr) return null;
        /** @var ArrayList $AL */
        $AL = ArrayList::create();
        foreach ($pr->summaryFields() as $name) {
//            $name = $field->getName();
            $value = $pr->{$name};
            if ($pr->has_one($name)) {
                $value = $pr->{$name}()->getTitle();
            }
            if ($pr->has_many($name) || $pr->many_many($name)) {
                $value = '';
                foreach ($pr->{$name}() as $rel) {
                    $value .= ($value?', ':'').$rel->getTitle();
                }
            }

            $AL->push(ArrayData::create(array(
                'Name' => $pr->fieldLabel($name),
                'Value' => $value,
            )));
        }
        return $AL;

    }

    public function ClientOrderForm() {
        $pr = $this->getRequestedProductRequest(true);
        if (!$pr) return $this->httpError(404);

        $fields = new FieldList();
        $arr = array();
        foreach ($pr->MachineCalculationItems() as $mci) {
            $arr[$mci->ID] = 'Price '.$mci->Price.', Price '.$mci->Price;
        }

//        $fields->push($cif = new LiteralField(
//            'CirculationItems',
//            $pr->renderWith('CalcItemsField')->forTemplate()
//        ));

//        $fields->push($cif = new OptionSetField(
//            'CirculationItemID',
//            'Circulation items all',
//            $arr
//        ));

        $fields->push($cif = new HiddenField('ProductRequestID','ProductRequestID', $pr->ID));

        $fields->push($cif = new CalcItemsSetField(
            'CalculationItemID',
            'Calculation items filtered',
            $pr
        ));

        $fields->push(new TextareaField('Comment','Comment'));

        $actions = new FieldList(
            FormAction::create("doSayHello")->setTitle("Say hello")
        );

        $required = new RequiredFields();

        $form = new Form($this, 'ClientOrderForm', $fields, $actions, $required);

        return $form;
    }

    public function doSayHello($data, Form $form) {
        if (!isset($data['CalculationItemID'])) return $this->httpError(404);
        /** @var \PC\CalculationItem $calcItem */
        $calcItem = \PC\CalculationItem::get()->byID((int) $data['CalculationItemID']);
        if (!$calcItem /*|| !$calcItem->canView()*/) return $this->httpError(404);

        $pr = $this->getRequestedProductRequest(true);
        if (!$pr) return $this->httpError(404);

        $user = $pr->User();
        if (!$user || !$user->exists()) {
            $user = new \PC\User();
            $user->Name = $pr->UserName;
            $user->Phone = $pr->UserPhone;
            $user->Email = $pr->UserEmail;
            $user->Password = $pr->UserEmail;
            $user->write();
            $pr->UserID = $user->ID;
        }

        if ($data) {
            $pr->UserComments = $data['Comment'];
            $pr->write();
        }

        $order = new \PC\ClientOrder();
        $order->UserID = $user->ID;
        $order->CalculationID = $calcItem->CalculationID;
        $order->BookNumber = $calcItem->Circulation;
        $order->Locale = $this->data()->Locale;
        $order->IsOffset = $calcItem->isOffset();
        $order->OrderStatusID = \PC\OrderStatus::getDefaultStatusId();
        $order->write();

//        Debug::dump($data);
//        Debug::dump($form);
//        die();

        return $this->redirect($this->Link('thnx'));
    }

    public function thnx() {
        $this->Content = 'Hello says: "Thanks!"';
        return $this->renderWith(array('Page','Page',));
    }



}















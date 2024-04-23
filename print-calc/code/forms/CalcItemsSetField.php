<?php
/**
 * Created by PhpStorm.
 * User: slavka
 * Date: 17.05.15
 * Time: 11:45
 */


class CalcItemsSetField extends OptionsetField {

    /** @var \PC\CalculationRequest */
    private $calculation_request = null;

    /**
     * @param \PC\CalculationRequest $calculation_request
     */
    public function setCalculationRequest($calculation_request) {
        $this->calculation_request = $calculation_request;
    }

    public function __construct($name, $title = null, \PC\CalculationRequest $calculation_request = null) {
        $this->calculation_request = $calculation_request;
        parent::__construct($name, $title);
    }

    public function Field($properties = array()) {
        $properties = array_merge($properties, array(
            'CalculationRequest' => $this->calculation_request,
        ));

        return $this->customise($properties)->renderWith(
            $this->getTemplates()
        );
    }


}
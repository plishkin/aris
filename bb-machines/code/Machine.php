<?php
// тестим бамбу мля!!!! пишите письма!!!
namespace BB;

use Utils\DataObject;

/**
 * Class Machine Очень классный класс!
 * @package BB
 * @property string Name
 * @property int SetupSeconds
 * @property int WasteItemsCount
 * @property float OperationPrice
 * @property float HourRatePrice
 * @method \HasManyList|\PC\MachineHourRate[] HourRates()
 * @method \ManyManyList|PaperFormat[] AllowedFormats()
 * @method PaperFormat MaxFormat()
 * @method PaperFormat MinFormat()
 * @method \ManyManyList|\BB\Machine\Variable[] Variables()
 */
class Machine extends \DataObject {

    private static $singular_name = "Machine";

    private static $plural_name = "Machines";

    private static $db = array(
        'Name' => 'Varchar(50)',
        'SetupSeconds' => 'Int',
        'WasteItemsCount' => 'Int',
        'OperationPrice' => 'Decimal(16)',
        'HourRatePrice' => 'Decimal(16)',
    );

    private static $has_many = array(
    );

    private static $has_one = array(
        'MaxFormat' => 'BB\PaperFormat',
        'MinFormat' => 'BB\PaperFormat',
    );

    private static $many_many = array(
        'AllowedFormats' => 'BB\PaperFormat',
        'Variables' => 'BB\Machine\Variable',
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->insertAfter('Name', DataObject::ClassNameDropdown($this));

        /** @var \GridField $grid */
        $grid = $fields->dataFieldByName('Variables');
//        $grid->getConfig()->addComponent(new \GridFieldManyRelationHandler(), 'GridFieldPaginator');

        return $fields;
    }

    /**
     * @param \PC\ProductRequest $pr
     * @param \PC\FlowOperationItem $flowOperationItem
     * @return bool
     */
    public function calculateProductRequestPrice($pr, $flowOperationItem = null) {
        return null;
    }

    /**
     * @param \PC\MachineCalculationItem $mci
     * @param $message
     * @param string $where
     * @param bool|false $write
     */
    protected function logTrace($mci, $message, $where='Price', $write = false){
        $mci->{$where.'Trace'} = $mci->{$where.'Trace'} . "\r\n".$message;
        if ($write) $mci->write();
    }

    /**
     * @param \PC\MachineCalculationItem $mci
     * @param $message
     * @param bool|false $write
     */
    protected function logPriceTrace($mci, $message, $write = false){
        $this->logTrace($mci, $message, 'Price', $write);
    }

    /**
     * @param \PC\MachineCalculationItem $mci
     * @param $message
     * @param bool|false $write
     */
    protected function logErrorTrace($mci, $message, $write = false){
        $this->logTrace($mci, $message, 'Error', $write);
    }

}











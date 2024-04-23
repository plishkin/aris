<?php
/**
 * Created by PhpStorm.
 * User: slavka
 * Date: 07.06.15
 * Time: 12:04
 */

namespace PC;
/**
 * Class MachineCalculationItem
 * @package PC
 * @property string PriceTrace
 * @property string ErrorMessages
 * @property string Status
 * @property string StatusMessage
 * @property int MachineID
 * @property int BBMachineID
 * @property float Price
 * @property int ProductRequestID
 * @property int FlowOperationItemID
 * @property int PaperStockSheetID
 * @method Machine Machine()
 * @method \BB\Machine BBMachine()
 * @method ProductRequest ProductRequest()
 * @method FlowOperationItem FlowOperationItem()
 * @method PaperStockSheet PaperStockSheet()
 */

class MachineCalculationItem extends \DataObject {

    private static $singular_name = "Machine Calculation Item";

    private static $plural_name = "Machine Calculation Items";

    private static $has_one = array(
        'Machine' => 'PC\Machine',
        'BBMachine' => 'BB\Machine',
        'ProductRequest' => 'PC\ProductRequest',
        'FlowOperationItem' => 'PC\FlowOperationItem',
        'PaperStockSheet' => 'PC\PaperStockSheet',
    );

    private static $many_many = array(
    );

    private static $belongs_many_many = array(
    );

    private static $db = array(
        'Price' => 'Double',
        'Status' => 'Varchar',
        'StatusMessage' => 'Varchar',
        'PriceTrace' => 'Text',
        'ErrorMessages' => 'Text',
    );

    private static $has_many = array(
    );

    private static $summary_fields = array(
        'ID', 'Created', 'Status', 'BBMachine.Name', 'StatusMessage', 'Price'
    );

    private static $searchable_fields = array(
        'ID', 'Created',
    );

    private static $default_sort = 'Created DESC';

}
<?php
/**
 * Created by PhpStorm.
 * User: slavka
 * Date: 16.05.15
 * Time: 16:19
 */

namespace PC;

/**
 * Class CalculationItem
 * @package PC
 * @property float Price
 * @property int Circulation
 * @property int CalculationID
 * @property string PrintingType
 * @property string Details
 * @method Calculation Calculation()
 * @method PaperStockSheet CoverPaperStockSheet()
 * @method PaperStockSheet BookPaperStockSheet()
 * @method Machine Machine()
 * @method DeliveryType DeliveryType()
 * @method FileFormat FileFormat()
 * @method AdditionalService AdditionalService()
 * @property int CoverPaperStockSheetID
 * @property int BookPaperStockSheetID
 * @property int MachineID
 * @property int DeliveryTypeID
 * @property int FileFormatID
 * @property int AdditionalServiceID
 */
class CalculationItem extends \DataObject {

    const DIGITAL = 'digital';
    const OFFSET = 'offset';

    private static $default_sort = '';

    private static $db = array(
        'Circulation' => 'Int',
        'Price' => 'Double',
        'Details' => 'Text',
        'PrintingType' => 'Enum("digital,offset","digital")',
    );

    private static $has_one = array(
        'Calculation' => 'PC\Calculation',
        'CoverPaperStockSheet' => 'PC\PaperStockSheet',
        'BookPaperStockSheet' => 'PC\PaperStockSheet',
        'Machine' => 'PC\Machine',
        'DeliveryType' => 'PC\DeliveryType',
        'FileFormat' => 'PC\FileFormat',
        'AdditionalService' => 'PC\AdditionalService',
    );

    public function isDigital() {
        return $this->PrintingType == self::DIGITAL;
    }

    public function isOffset() {
        return $this->PrintingType == self::OFFSET;
    }

    public function setPrintingByType($type) {
        $this->PrintingType = $type;
    }

}
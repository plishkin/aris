<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 22.03.12
 * Time: 12:13
 *
 */

namespace BB;
/**
 * Class PaperFormat
 * @package BB
 * @property string Title
 * @property string Abbr
 * @property string Description
 * @property float Width
 * @property float Height
 * @property float FoldedWidth
 * @property float FoldedHeight
 * @property int FoldedSheetsQuantity
 * @property int FolderingQuantity
 */
class PaperFormat extends \DataObject {

    private static $db = array(
        'Title' => 'Varchar(128)',
        'Abbr' => 'Varchar(32)',
        'Description' => 'Text',
        'Width' => 'Float',
        'Height' => 'Float',
        'FoldedWidth' => 'Float',
        'FoldedHeight' => 'Float',
        'FoldedSheetsQuantity' => 'Int',
        'FolderingQuantity' => 'Int',
    );

    private static $has_many = array(
        'PaperStockSheets' => 'PC\PaperStockSheet',
        'ProductRequests' => 'PC\ProductRequest',
        'BookFormats' => 'PC\BookFormat',
    );

    private  static $summary_fields = array(
        'Title' => 'Title',
        'Abbr' => 'Abbr',
        'Width' => 'Width',
        'Height' => 'Height',
    );

    private static $belongs_many_many = array(
        'Machines' => 'PC\Machine',
    );

    private static $singular_name = 'Paper Format';

    private static $plural_name = 'Paper Formats';

    private static $add_action = 'a Paper Format';

    public function forTemplate() {
        return $this->has_extension('TranslatableDataObject') ? $this->T("Title") : $this->Title;
    }

    /**
     * @param PaperFormat $paperFormat
     * @param PaperFormat $parentPaperFormat
     * @param string $roundFunction
     * @return int|float|bool
     */
    public static function calculatePaperFormatLayoutQuantity($paperFormat, $parentPaperFormat, $roundFunction = 'floor') {
        if (!$paperFormat || !$parentPaperFormat) return false;
        $W = $parentPaperFormat->Width;
        $H = $parentPaperFormat->Height;
        $w = $paperFormat->Width;
        $h = $paperFormat->Height;
//        Debug::dump($W);
//        Debug::dump($H);
//        Debug::dump($w);
//        Debug::dump($h);
        if (!($W * $H) > 0 || !($w * $h) > 0) return 0;
//        $w = intval($width);
//        $h = intval($height);
        if (in_array($roundFunction, array('floor', 'ceil', 'round'))) {
            $qv = intval($roundFunction($W / $w) * $roundFunction($H / $h));
            $qh = intval($roundFunction($H / $w) * $roundFunction($W / $h));
            return intval(($qv > $qh) ? $qv : $qh);
        }
        return ($W * $H) / ($w * $h);
    }


    // ---> PaperFormatTrait START <---

    public function getDimensionString() {
        $return = $this->Width . 'x' . $this->Height . ' '._t('Units.mm','mm');
        if (!$this->Width || !$this->Height) $return = _t('DataObject.CUSTOM','Custom');
        return $return;
    }

    public function getQuantitySpreadedOutOnFormatOf(PaperFormat $paperFormat, $roundFunction = 'floor') {
        $child = static::getPaperFormatFromDataObject($this);
        return PaperFormat::calculatePaperFormatLayoutQuantity($child,$paperFormat,$roundFunction);
    }

    public function getQuantityFormatSpreadedOut(PaperFormat $paperFormat, $roundFunction = 'floor') {
        $parent = static::getPaperFormatFromDataObject($this);
        return PaperFormat::calculatePaperFormatLayoutQuantity($paperFormat,$parent,$roundFunction);
    }

    /**
     * @param \DataObject $DataObject
     * @return PaperFormat
     */
    public static function getPaperFormatFromDataObject(\DataObject $DataObject){
        $PaperFormat = \DataObject::create();
        if ($DataObject->hasField('PaperFormat')) {
            try {
                $PaperFormatRelated = $DataObject->PaperFormat();
                $PaperFormat = $PaperFormatRelated;
            } catch (\Exception $e) {}
        }
        if ($DataObject->Width && $DataObject->Height) {
            $PaperFormat = new PaperFormat();
            $PaperFormat->Width = $DataObject->Width;
            $PaperFormat->Height = $DataObject->Height;
        }
        return $PaperFormat;
    }

    public function getSquare() {return $this->Width * $this->Height;}

    public function isTall() {return ($this->Width / $this->Height) < 1;}
    public function isWide() {return ($this->Width / $this->Height) > 1;}

    public function swapWidthHeight(){
        $swap = $this->Width;
        $this->Width = $this->Height;
        $this->Height = $swap;
    }

    // ---> PaperFormatTrait END <---


}

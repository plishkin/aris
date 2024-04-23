<?php

namespace BB;

use PC\MachineCalculationItem;
use Utils\FrontEnd;
use Utils\GriedFieldExtraFieldsUtils;

/**
 * Class PressMachine
 * @package BB
 * @property int FormSetupSeconds
 * @property int FormSetupSheets
 * @property float SetupFormPrice
 * @property bool IsDoubleSide
 * @property int SeparationsCount
 * @property float NonPrintableWidth
 * @property float NonPrintableHeight
 * @property string ABprint
 * @method \ManyManyList|PaperDensityGroup[] PaperDensityGroups()
 * @method \ManyManyList|Machine\PaperWeight[] AllowedPaperWeights()
 * @method \ManyManyList|PaperType[] AllowedPaperTypes()
 * @method \ManyManyList|Chromaticity[] AllowedChromaticities()
 */

class PressMachine extends Machine {
    
    private static $singular_name = "Press Machine";
    
    private static $plural_name = "Press Machines";
    
    private static $db = array(
        'FormSetupSeconds' => 'Int',
        'FormSetupSheets' => 'Int',
        'SetupFormPrice' => 'Decimal(16)',
        'IsDoubleSide' => 'Boolean(0)',
        'SeparationsCount' => 'Int(1)',
        'NonPrintableWidth' => 'Float(0)',
        'NonPrintableHeight' => 'Float(0)',
        'ABprint' => 'Enum("None,A/B print manual,A/B print perfector","None")',
    );

    private static $has_many = array(
    );

    private static $has_one = array(
    );

    private static $many_many = array(
        'PaperDensityGroups' => 'BB\PaperDensityGroup',
        'AllowedPaperWeights' => 'BB\Machine\PaperWeight',
        'AllowedPaperTypes' => 'BB\PaperType',
        'AllowedChromaticities' => 'BB\Chromaticity',
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        foreach ($this->PaperDensityGroups() as $group) {
            foreach ($group->PaperDensities() as $density) {
                $MachinePaperWeight = $this->AllowedPaperWeights()->filter(
                    'PaperDensityID', $density->ID
                )->first();
                if (!$MachinePaperWeight || !$MachinePaperWeight->exists()) {
                    $MachinePaperWeight = new Machine\PaperWeight();
                    $MachinePaperWeight->PaperDensityID = $density->ID;
                    $MachinePaperWeight->write();
                    $this->AllowedPaperWeights()->add($MachinePaperWeight);
                }
            }
        }
        $fields->replaceField(
            'AllowedPaperWeights',
            GriedFieldExtraFieldsUtils::create($this)->getExtraGridField('AllowedPaperWeights')
        );

        return $fields;
    }

    public function isABprintManual() {
        return $this->ABprint == 'A/B print manual';
    }

    public function isABprintPerfector() {
        return $this->ABprint == 'A/B print perfector';
    }

    public function getPrintableFormat(PaperFormat $source_format) {
        $format = new PaperFormat();
        $format->Width = $source_format->Width - $this->NonPrintableWidth;
        $format->Height = $source_format->Height - $this->NonPrintableHeight;
        return $format;
    }

    public function getAvailablePropertyIDs($name,$params=null) {
        $name = 'getAvailable'.$name.'IDs';
        return method_exists($this,$name) ? $this->{$name}($params) : array();
    }

    public function getAvailablePaperDensityIDs($params=null) {
        $machineDensityIDs = $this->AllowedPaperWeights()->map('PaperDensityID','PaperDensityID')->toArray();
        $stockSheetDensityIDs = FrontEnd::getFor('PC\PaperStockSheet')->map('PaperDensityID','PaperDensityID')->toArray();
        return array_intersect($machineDensityIDs,$stockSheetDensityIDs);
    }

    public function getAvailablePaperTypeIDs($params=null) {
        return $this->AllowedPaperTypes()->map('ID','ID')->toArray();
    }

    public function getAvailablePaperFormatIDs($params=null) {
        $formats = array();
        if (isset($params['product'])) {
            /** @var \PC\Product $product */
            $product = $params['product'];
            foreach ($product->PaperFormats() as $format) {
                $productFormatWithBleeds = $product->getFormatWithBleeds($format);
                /** @var PaperFormat $machineFormat */
                foreach ($this->AllowedFormats() as $machineFormat) {
                    $printableFormat = $this->getPrintableFormat($machineFormat);
                    if ($printableFormat->getQuantityFormatSpreadedOut($productFormatWithBleeds)>0) {
                        $formats[$format->ID] = $format->ID;
                    }
                }
            }
        }
//        \Debug::dump($formats);
        return $formats;
//        return $formats ? $formats : $this->AllowedFormats()->map('ID','ID')->toArray();
    }

    public function getAvailableChromaticityIDs($params=null) {
        return $this->AllowedChromaticities()->map('ID','ID')->toArray();
    }

    public function calculateProductRequestPrice($pr, $flowOperationItem = null) {
        $arr = array('price'=>-1,'log'=>'','log_error'=>'','strict'=>false);

        $format = $pr->getFormatWithBleeds();
        foreach ($this->AllowedFormats() as $machinePaperFormat) {
            $mci = new MachineCalculationItem();
            $mci->ProductRequestID = $pr->ID;
            $mci->BBMachineID = $this->ID;
            if ($flowOperationItem) $mci->FlowOperationItemID = $flowOperationItem->ID;
            $mci->write();

            $printableFormat = $this->getPrintableFormat($machinePaperFormat);
            $this->logPriceTrace($mci,'------------ Machine Printable Format -> '.$printableFormat->getDimensionString());
            $this->logPriceTrace($mci,'------------ Machine Paper Format -> '.$machinePaperFormat->getDimensionString());
            $q = $printableFormat->getQuantityFormatSpreadedOut($format);
            if ($this->isABprintManual() || $this->isABprintPerfector()) {
                $abManualFormat = new PaperFormat();
                $abManualFormat->Width = $printableFormat->Width;
                $abManualFormat->Height = $printableFormat->Height;
                if ($this->isABprintManual())
                    $side = $printableFormat->Width > $printableFormat->Height ? 'Width' : 'Height';
                if ($this->isABprintPerfector())
                    $side = $printableFormat->Width < $printableFormat->Height ? 'Width' : 'Height';
                $abManualFormat->{$side} = $printableFormat->{$side} / 2;
                $q = $abManualFormat->getQuantityFormatSpreadedOut($format) * 2;
            }
            $this->logPriceTrace($mci,'Product Format -> '.$format->Title);
            $this->logPriceTrace($mci,'Product per Machine Paper Format = '.$q);
            if (!($q>0)) {
                $mci->Status = 'Fail';
                $mci->StatusMessage = 'Product paper formet is bigger than printable area';
                $this->logErrorTrace($mci,$mci->StatusMessage,true);
                continue;
            }
            /** @var \PC\PaperStockSheet[]|\DataList $sheets */
            $sheets = FrontEnd::getFor('PC\PaperStockSheet')->filter(array(
                'PaperTypeID'=>$pr->PaperTypeID,
                'PaperDensityID'=>$pr->PaperDensityID,
            ));
            if (!$sheets || !$sheets->exists()) {
                $mci->Status = 'Fail';
                $mci->StatusMessage = 'No Paper Stock Sheets available';
                $this->logErrorTrace($mci,$mci->StatusMessage,true);
                continue;
            }
            $printingSheetsCount = ceil($pr->Circulation/$q) * ceil($pr->PagesCount/2);
            $this->logPriceTrace($mci,'Printing Sheets Count = '.$printingSheetsCount);
            $signatureCount = ceil(ceil($pr->PagesCount/2) / $q);
            if ($this->isABprintManual() || $this->isABprintPerfector()) {
                $signatureCount = ceil(ceil($pr->PagesCount/2) / ($q / 2));
            }
            $this->logPriceTrace($mci,'Signature Count = '.$signatureCount);
            $printingSheetsCount += $signatureCount * $this->FormSetupSheets + $this->WasteItemsCount;
            $this->logPriceTrace($mci,'Total Printing Sheets Count = '.$printingSheetsCount);

            // -------------- Paper Price --------------
            $lowestSheetPrice = -1;
            foreach ($sheets as $sheet) {
                $this->logPriceTrace($mci,'------------ Paper Stock Sheet -> '.$sheet->Name);
                $qm = $sheet->PaperFormat()->getQuantityFormatSpreadedOut($machinePaperFormat);
                $this->logPriceTrace($mci,'Machine Paper Format per Stock Sheet = '.$qm);
                if (!($qm>0)) continue;
                $sheetsCount = ceil($printingSheetsCount / $qm);
                $this->logPriceTrace($mci,'Stock Sheet count = '.$sheetsCount);
                $price = $sheetsCount * $sheet->Cost;
                $this->logPriceTrace($mci,'Paper Stock Sheet price = '.$price);
                if (!($lowestSheetPrice>0) || $price<$lowestSheetPrice) {
                    $lowestSheetPrice = $price;
                    $mci->PaperStockSheetID = $sheet->ID;
                }
            }
            $printing_time_price = 0;
            // -------------- Total Print Price --------------
            $fitting_time = ($this->SetupSeconds + $this->FormSetupSeconds * $signatureCount)/3600;
            $this->logPriceTrace($mci,'Setup time = '.$fitting_time);
            $machinePaperWeight = $this->AllowedPaperWeights()->filter('PaperDensityID',$pr->PaperDensityID)->first();
            if ($machinePaperWeight && $machinePaperWeight->exists()) {
                $printing_time = $printingSheetsCount / $machinePaperWeight->PrintSpeed;
                $this->logPriceTrace($mci,'Printing time = '.$printing_time);
                $printing_time_price = ($fitting_time + $printing_time) * $this->HourRatePrice;
                $this->logPriceTrace($mci,'Setup & Printing time price = '.$printing_time_price);
            } else {
                $arr['log_error'] .= "\r\n".'Couldn\'t find Machine Paper Weight = '
                    .$this->Name.' '.$machinePaperFormat->getDimensionString();
            }
            $plates_price = $this->SetupFormPrice * $signatureCount;
            $this->logPriceTrace($mci,'Plates price = '.$plates_price);
            $price = $lowestSheetPrice + $printing_time_price + $plates_price;

            $var = $this->Variables()->filter('Expression','PrintingSheetsCount')->first();
            if ($var) {
                $printingSheetsCountPrice = $this->OperationPrice * $printingSheetsCount;
                $this->logPriceTrace($mci,'Printing Sheets Count Price = '.$printingSheetsCountPrice);
                $price += $printingSheetsCountPrice;
            }

            $mci->Status = 'Success';
            $mci->Price = $price;
            $this->logPriceTrace($mci,'Total price = '.$price,true);

        }
        /** @var \PC\MachineCalculationItem $mciLowest */
        $mciLowest = $pr->MachineCalculationItems()->sort('Price ASC')->where('Price > 0')->first();
        return $mciLowest ? $mciLowest->Price : false;
    }

}











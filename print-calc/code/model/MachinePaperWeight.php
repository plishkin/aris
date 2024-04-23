<?php

namespace PC;

/**
 * This class has been auto-generated by the SS importer module
 *
 * @property string Name
 * @property int PaperDensityID
 * @method \ManyManyList Machines()
 * @method \BB\PaperDensity PaperDensity()
 */

class MachinePaperWeight extends \DataObject {

    private static $singular_name = "Paper Weight";

    private static $plural_name = "Paper Weights";

    private static $belongs_many_many = array(
        'Machines' => 'PC\Machine',
    );

    private static $db = array(
        'Name' => 'Varchar(100)',
        'PrintSpeed' => 'Int',
    );

    private static $has_one = array(
        'PaperDensity' => 'BB\PaperDensity',
    );

    private static $summary_fields = array(
        'Name' => 'Name',
		'PaperDensity.Title' => 'PaperDensity',
		'PrintSpeed' => 'PrintSpeed'
    );

    private static $searchable_fields = array(
        'Name','PrintSpeed'
    );

    public function getWeight() {
        return $this->PaperDensity()->Amount;
    }


}

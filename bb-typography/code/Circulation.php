<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 19.03.12
 * Time: 15:47
 */

namespace BB;
/**
 * Class Circulation
 * @package BB
 * @property string Title
 * @property string Abbr
 * @property string Description
 * @property int Amount
 * @method \ManyManyList FlowSpusks() FlowSpusks($filter = "", $sort = "", $join = "", $limit = "")
 */
class Circulation extends \DataObject implements Numerable {
    private static $db = array(
        'Title' => 'Varchar(128)',
        'Abbr' => 'Varchar(32)',
        'Description' => 'Text',
        'Amount' => 'Int',
    );

    private static $belongs_many_many = array(
    );

    public function forTemplate() {
        $units = _t("Units.items", "");
        return $this->Amount . ($units ? ' ' . $units : '');
    }

    public static $frontend_base_sql = '';

    private static $default_sort = array(
        '"Title" * 1 ' => 'ASC',
        '"Title" ' => 'ASC',
    );

    private static $singular_name = 'Circulation';

    private static $plural_name = 'Circulations';

    private static $add_action = 'a Circulation';

    private $LoadedOrCreatedCache = 'loaded';

    public function isJustCreated() {
        return $this->LoadedOrCreatedCache == 'created';
    }

    public function isJustLoaded() {
        return $this->LoadedOrCreatedCache == 'loaded';
    }

    public static function loadOrCreateByAmount($Amount, $write = true) {
        /** @var Circulation|null $Circulation */
        $Circulation = Circulation::get()->filter('Amount', $Amount)->first();
        if (!$Circulation) {
            /** @var Circulation $Circulation */
            $Circulation = Circulation::create();
            $Circulation->Title = $Amount;
            $Circulation->Abbr = $Amount;
            $Circulation->Description = $Amount;
            $Circulation->Amount = $Amount;
        }
        if ($write && !$Circulation->exists()) {
            $Circulation->ID = $Circulation->write();
            $Circulation->LoadedOrCreatedCache = 'created';
        }
        return $Circulation;
    }

}

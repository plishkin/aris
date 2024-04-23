<?php

namespace Utils;

class FrontEnd {

    private static function getFrontendFields(){
        $arr = array('IsVisible'=>1,'IsActive'=>1,'Published'=>1,'IsPublished'=>1,'Live'=>1,'UserStatus:not'=>'DELETED');
        if (class_exists('Subsite') && !\Subsite::$disable_subsite_filter) {
            if (!\Director::is_cli()) $arr['SubsiteID'] = \Subsite::currentSubsiteID();
        }
        return $arr;
    }

    /**
     * @param $class
     * @param bool $proccessDefaults
     * @return \DataList|null
     */
    public static function getFor($class,$proccessDefaults=true) {
        if (!$class || !class_exists($class)) return null;
        return self::filterDataList($class::get());
    }

    public static function filter($DataListOrDataObject=null) {
        if (!$DataListOrDataObject) return null;
        if (is_subclass_of($DataListOrDataObject,'\DataObject')) {
            return self::filterDataObject($DataListOrDataObject);
        }
        return self::filterDataList($DataListOrDataObject);
    }

    public static function filterDataObject($DataObject=null) {
        if (!$DataObject) return null;
        $dl = self::getFor(get_class($DataObject),true);
        return $dl?$dl->byID($DataObject->ID):$dl;
    }

    /**
     * @param \DataList $DataList
     * @param bool $proccessDefaults
     * @return \DataList|null
     */
    public static function filterDataList($DataList=null,$proccessDefaults=true) {
        if (!$DataList) return null;
        $class = $DataList->dataClass();
        /** @var \DataObject $do */
        $do = $class::create();
        $DL = clone $DataList;
        if (method_exists($class,'getFrontendDataList')){
            $DL = $class::getFrontendDataList();
            if (!$proccessDefaults) return $DL;
        }
        if (property_exists($class,'frontend_base_sql')){
            $name = 'frontend_base_sql';
            $sql = $do::$$name;
            $DL = $DL->where($sql);
        }
        $filter = array();
        foreach (self::getFrontendFields() as $key => $val) {
            $exDotted = explode(':',$key);
            $fieldName = reset($exDotted);
            if ($do->hasDatabaseField($fieldName)) $filter[$class.'.'.$key] = $val;
        }
        if(class_exists('\Translatable') && $do->has_extension('Translatable')){
            $filter[$class.'.'.'Locale'] = \Translatable::get_current_locale();
        }
        if ($filter) $DL = $DL->filter($filter);
        return $DL;
    }

}
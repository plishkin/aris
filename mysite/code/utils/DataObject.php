<?php
/**
 * Created by PhpStorm.
 * User: slavka
 * Date: 24.03.14
 * Time: 17:48
 */

namespace Utils;


class DataObject {

    public static function ClassNameDropdown(\DataObject $do, $title = 'Type',$none=true,$simpleNames=true,$class_filter='') {
        $class = get_class($do);
        $baseDataClass = \ClassInfo::baseDataClass($class);
        $classes = \ClassInfo::subclassesFor($baseDataClass);
        if ($none) $classes[$baseDataClass] = 'None';
        $return = array();
        foreach ($classes as $key => $value) {
            if ($class_filter && $value!="None" && !preg_match($class_filter,$value)) continue;
            if ($simpleNames) {
                $ex = explode('\\',$value);
                $value = end($ex);
            }
            $return[$key] = $value;
        }
        return new \DropdownField('ClassName',$title,$return,$do->ClassName);
    }

    public static function updateFromNative(\DataObject $do, $record,$convertTosql=false) {
        $newFields = array();
        foreach ($record as $key => $value) {
            $field = $key;
            if (strtolower($key)=='id') $field='ID';
            $field = Utils::toClassName($field);
            if ($do->hasDatabaseField($field)) {
                $val = $convertTosql?\Convert::raw2sql($value):$value;
                if ($do->{$field}!=$val) $do->{$field} = $val;
            }
            else {
                if (isset($newFields[$key])) {
                    if (!$newFields[$key]['SampleValue'] && $value){
                        $newFields[$key]['SampleValue'] = $value;
                    }
                } else {
                    $newFields[$key] = array(
                        'OriginalKey' => $key,
                        'Field' => $field,
                        'SampleValue' => $value,
                    );
                }
            }
        }
        return $newFields;
    }

} 
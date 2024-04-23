<?php

namespace Utils;

class Utils {
    const maxInt = 2147483647;

    public static function bytesToString($a_bytes) {
        if ($a_bytes < 1024) {
            return $a_bytes . ' B';
        } elseif ($a_bytes < 1048576) {
            return round($a_bytes / 1024, 2) . ' KiB';
        } elseif ($a_bytes < 1073741824) {
            return round($a_bytes / 1048576, 2) . ' MiB';
        } elseif ($a_bytes < 1099511627776) {
            return round($a_bytes / 1073741824, 2) . ' GiB';
        } elseif ($a_bytes < 1125899906842624) {
            return round($a_bytes / 1099511627776, 2) . ' TiB';
        } elseif ($a_bytes < 1152921504606846976) {
            return round($a_bytes / 1125899906842624, 2) . ' PiB';
        } elseif ($a_bytes < 1180591620717411303424) {
            return round($a_bytes / 1152921504606846976, 2) . ' EiB';
        } elseif ($a_bytes < 1208925819614629174706176) {
            return round($a_bytes / 1180591620717411303424, 2) . ' ZiB';
        } else {
            return round($a_bytes / 1208925819614629174706176, 2) . ' YiB';
        }
    }

    public static function stringToBytes($string) {
        $matches = array();
        preg_match('/(.+)(\w+)$/', $string, $matches);
        $pow = array('b' => 0, 'k' => 1, 'm' => 2, 'g' => 3);
        if (!$matches) return 0;
        return $matches[1] && $matches[2] && $matches[2][0] ? $matches[1] * pow(1024, $pow[strtolower($matches[2][0])]) : 0;
    }

    /**
     * @static
     * @param string $s
     * @return string
     */
    public static function toPHPvar($s) {
        $s = self::toVar($s, ' ');
        $s = str_replace(array('_', '.'), ' ', $s);
        $s = ucwords($s);
        $s = str_replace(' ', '', $s);
        return $s;
    }

    /**
     * @static
     * @param string $s
     * @param bool $useCaps
     * @return string
     */
    public static function toCSSclassName($s, $useCaps = false) {
        if (!$useCaps) $s = strtolower($s);
        $s = preg_replace('/\s+/us', '-', self::toVarCharacters($s, ' '));
        return $s;
    }

    /**
     * @param string $rawID
     * @return int
     */
    public static function toID($rawID = '') {
        return intval(substr(preg_replace('/\D/i', '', $rawID), 0, 11));
    }

    /**
     * @static
     * @param string $s
     * @return string
     */
    public static function toVar($s) {
        return preg_replace('/^\d+/', '', self::toVarCharacters($s, ''));
    }

    /**
     * @static
     * @param string $s
     * @param string $replacement
     * @return string
     */
    public static function toVarCharacters($s, $replacement = '') {
        return preg_replace('/[^\w\d_]/', $replacement, $s);
    }


    /**
     * @param string $rawIDString
     * @param string $separator
     * @return mixed
     */
    public static function toIDString($rawIDString = '', $separator = ',') {
        return preg_replace('/[^\d' . $separator . ']/i', '', $rawIDString . '');
    }

    public static function toIDsArrayFromIDString($rawIDString = '', $delimeters = array(',', '|')) {
        $filtered = str_replace($delimeters, ',', $rawIDString);
        $filtered = \Utils::toIDString($filtered);
        return explode(',', $filtered);
    }

    public static function toClassName($rawClassName) {
        $s = preg_replace('/(^\d*|[^\w\d_\\\\])/', '', $rawClassName);
        $s = str_replace(array('_', '.'), ' ', $s);
        $s = ucwords($s);
        $s = str_replace(' ', '', $s);
        return substr($s, 0, 40);
    }

    public static function addSpacesToCamelCase($string) {
        return preg_replace('/(?<!^)((?<![[:upper:]])[[:upper:]]|[[:upper:]](?![[:upper:]]))/',' $1',$string);
    }

    /**
     * @static
     * @param string $s
     * @param int $maxCharactersQuantity
     * @return string
     */
    public static function trim2quantity($s = '', $maxCharactersQuantity = 150) {
        if (!$s) return false;
        if ($s == '') return $s;
        if (!$maxCharactersQuantity) {
            $maxCharactersQuantity = 150;
        }
        $text = $s;
        if (mb_strlen($text) > $maxCharactersQuantity) {
            $i = $maxCharactersQuantity;
            while ((trim(mb_substr($text, $i - 1, 1)) == '') && ($i > 0)) {
                $i--;
            }
            $trimmedparagraph = mb_substr($text, 0, $i);
            $punk = '...';
            if (mb_substr($trimmedparagraph, -3, 3) == '...') {
                $punk = '';
            }
            if (mb_substr($trimmedparagraph, -1, 1) == '?') {
                $punk = '..';
            }
            if (mb_substr($trimmedparagraph, -1, 1) == '!') {
                $punk = '..';
            }
            return $trimmedparagraph . $punk;
        }
        return $text;
    }

    /**
     * @static
     * @param string $s
     * @param int $maxCharactersQuantity
     * @param int $lastQuantity
     * @return bool|string
     */
    public static function trim2quantityMiddle($s = '', $maxCharactersQuantity = 150, $lastQuantity = 3) {
        if (!$s) return false;
        if ($s == '') return $s;
        if (!$maxCharactersQuantity) {
            $maxCharactersQuantity = 150;
        }
        $text = $s;
        if (mb_strlen($text) > $maxCharactersQuantity) {
            $i = $maxCharactersQuantity;
//            while((trim(mb_substr($text, $i - 1, 1)) == '') && ($i>0)) {$i--;}
            $trimmedparagraph = mb_substr($text, 0, $i - $lastQuantity, 'utf-8');
            $trimmedparagraphSuffix = mb_substr($s, -$lastQuantity, $lastQuantity, 'utf-8');
            $punk = '...';

            return $trimmedparagraph . $punk . $trimmedparagraphSuffix;
        }
        return $text;
    }

    /**
     * @static
     * @param int $val
     * @param int $base
     * @param string $chars
     * @return string
     */
    public static function encode($val, $base = 62, $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        // can't handle numbers larger than 2^31-1 = 2147483647
        $str = '';
        do {
            $i = $val % $base;
            $str = $chars[$i] . $str;
            $val = ($val - $i) / $base;
        } while ($val > 0);
        return $str;
    }

    /**
     * @static
     * @param string $str
     * @param int $base
     * @param string $chars
     * @return int
     */
    public static function decode($str, $base = 62, $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $len = strlen($str);
        $val = 0;
        $arr = array_flip(str_split($chars));
        for ($i = 0; $i < $len; ++$i) {
            $val += $arr[$str[$i]] * pow($base, $len - $i - 1);
        }
        return $val;
    }

    /**
     * @static
     * @param string $timestamp
     * @return string
     */
    public static function getFormatedUAdate($timestamp) {
        if (strlen($timestamp) > 0) {
            return date('d.m.Y', $timestamp);
        } else return '';
    }

    /**
     * @static
     * @param string $timestamp
     * @return string
     */
    public static function getFormatedUAdatetime($timestamp) {
        return date('H:i d.m.Y', $timestamp);
    }

    /**
     * @param $str
     * @return string
     */
    public static function imTranslite($str) {
        // транслитерация корректно работает на страницах с любой кодировкой
        // (c)Imbolc http://php.imbolc.name

        static $tbl = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ж' => 'g', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
            'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'ы' => 'i', 'э' => 'e', 'А' => 'A',
            'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ж' => 'G', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Ы' => 'I', 'Э' => 'E', 'ё' => "yo", 'х' => "h",
            'ц' => "ts", 'ч' => "ch", 'ш' => "sh", 'щ' => "shch", 'ъ' => "", 'ь' => "", 'ю' => "yu", 'я' => "ya",
            'Ё' => "YO", 'Х' => "H", 'Ц' => "TS", 'Ч' => "CH", 'Ш' => "SH", 'Щ' => "SHCH", 'Ъ' => "", 'Ь' => "",
            'Ю' => "YU", 'Я' => "YA", 'і' => "i", 'І' => "I", 'ґ' => "g", 'Ґ' => "G", 'Є' => "Ye", 'є' => "ye", 'Ї' => "Yi", 'ї' => "yi", " " => "_"
        );
        $s = strtr($str, $tbl);
        $s = preg_replace('/[^a-zA-Z0-9._-]/', '', $s);
        return $s;
    }


    /**
     * @param string $path
     * @return string mixed
     */
    public static function fixPath4Windows($path) {
        $return = str_replace('/', '\\', $path);
        $return = str_replace('\\.\\', '\\', $return);
        return $return;
    }

    /**
     * @static
     * @param string $filename
     * @return string
     */
    public static function fixFileName($filename) {
        $return = mb_ereg_replace('[^\w\d_.]', '', $filename);
        return $return;
    }

    /**
     * @static
     * @param array|string $value
     * @return array|string
     */
    public static function stripslashes_deep($value) {
        $value = is_array($value) ?
            array_map('Utils::stripslashes_deep', $value) :
            stripslashes($value);

        return $value;
    }

    public static function dump($var, $echo = true) {
        if (!$var) $var = 'false';
        $s = '<pre>Variable=' . print_r($var, true) . '</pre>';
        if ($echo) echo $s;
        else return $s;
    }

    /**
     * @static
     * @param $obj
     * @return string
     */
    public static function serialize($obj) {
        return base64_encode(@gzcompress(@serialize($obj)));
    }

    /**
     * @static
     * @param string $txt
     * @return mixed
     */
    public static function unserialize($txt) {
        return @unserialize(@gzuncompress(base64_decode($txt)));
    }

    public static function getDirectoryList($directory, $filter = array()) {
        if (!is_array($filter)) $filter = array($filter);
        $results = array();
        $handler = opendir($directory);
        while ($file = readdir($handler)) {
            if ($file != "." && $file != "..") {
                foreach ($filter as $end) {
                    if (Utils::EndsWith($file, $end)) $results[] = $file;
                }
            }
        }
        closedir($handler);
        return $results;
    }


    /**
     * @static
     * @param string $className
     * @param array $arr
     * @return array
     */
    public static function parseRequestEntityValues($className, $arr = array()) {
        $brr = array();
        if (class_exists($className)) {
            $obj = new $className();
            foreach ($arr as $key => $value) {
                $key = ($key == 'entity_id') ? 'id' : $key;
                $name = 'get' . Utils::toPHPvar($key);
                //echo($key.'='.$value);
                if (method_exists($obj, $name)) {
                    $var = $obj->$name();
                    $type = gettype($var);
                    if ($type == 'string') $brr[$key] = strip_tags($value);
                    if ($type == 'int' || $type == 'integer') $brr[$key] = intval(strip_tags($value));
                    if ($type == 'float' || $type == 'double') $brr[$key] = floatval(strip_tags($value));
                    //echo('|'.$type.'<br>');
                }
                //echo('|'.$brr[$key].'<br>');
            }
        }
        return $brr;
    }

    /**
     * @static
     * @param string|array $s
     * @return string|array
     */
    public static function parseRequestString($s = '') {
        if (is_array($s)) {
            $arr = array();
            foreach ($s as $key => $value) {
                $keyNew = self::parseRequestString($key);
                $valueNew = self::parseRequestString($value);
                $valueNew = (Utils::EndsWith($keyNew, '_id')) ? intval($valueNew) : $valueNew;
                if ($keyNew && $valueNew && !isset($arr[$keyNew])) $arr[$keyNew] = $valueNew;
            }
            return $arr;
        }
        $return = trim(strip_tags($s));
        return $return;
    }

    public static function striptags($s = '') {
        return self::mb_trim(strip_tags($s));
    }

    public static function urlencode($s) {
        return htmlspecialchars(trim($s));
    }

    public static function rangeFromTo($from = '', $separator = '', $to = '') {

    }

    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            return @rmdir($dir);
        } else return FALSE;
    }

    public static function NotifyAdminByEmail($message = '', $subject = '') {
        $emails = \SiteConfig::current_site_config()->AdminEmails;
        if (!$emails || !$message) return false;
        $emails = explode(',', $emails);
        $subject = $subject ? $subject : $_SERVER['HTTP_HOST'] . " Notifier";
        foreach ($emails as $emailTo) {
            $email = new \Email();
            $email->setSubject($subject);
            $email->setTo($emailTo);
            $email->setFrom($_SERVER['HTTP_HOST'] . ' < notifier@' . $_SERVER['HTTP_HOST'] . ' >');
            $email->setBody($message);
            $email->send();
        }
    }

    public static function generateBackTrace() {

    }

    public static function mkdir($pathname, $mode = 0777, $recursive = false, $context = null) {
        if ($context) return mkdir($pathname, $mode, $recursive, $context) && chmod($pathname, $mode);
        return mkdir($pathname, $mode, $recursive) && chmod($pathname, $mode);
    }

    public static $isMustDieBrowserCache = null;

    public static function isMustDieBrowser() {
        if (self::$isMustDieBrowserCache !== null) return self::$isMustDieBrowserCache;
        $match = array(
            \Utils\Browser::BROWSER_IE => '10',
//            Browser::BROWSER_OPERA => '11',
//            Browser::BROWSER_FIREFOX => '10.0.3',
//            Browser::BROWSER_SAFARI => '5.1.5',
//            Browser::BROWSER_CHROME => '18.0'
        );
        $browser = new \Utils\Browser();
        if (isset($match[$browser->getBrowser()])) {
            return self::$isMustDieBrowserCache =
                (version_compare($browser->getVersion(), $match[$browser->getBrowser()]) == -1);
        }
        return false;
    }

    public static $isOldForSSLBrowserCache = null;

    public static function isOldForSSLBrowser() {
        if (self::$isOldForSSLBrowserCache !== null) return self::$isOldForSSLBrowserCache;
        $match = array(
            \Utils\Browser::BROWSER_IE => '9',
            \Utils\Browser::BROWSER_OPERA => '11',
            \Utils\Browser::BROWSER_FIREFOX => '10.0.3',
            \Utils\Browser::BROWSER_SAFARI => '5.1.5',
            \Utils\Browser::BROWSER_CHROME => '18.0'
        );
        $browser = new \Utils\Browser();
        if (isset($match[$browser->getBrowser()])) {
            return self::$isOldForSSLBrowserCache =
                (version_compare($browser->getVersion(), $match[$browser->getBrowser()]) == -1);
        }
        return false;
    }

    public static function timestampToSQLDate($timestamp, $strtotime = '') {
        $ts = $timestamp;
        if ($strtotime) $ts = strtotime($strtotime, $timestamp);
        return $ts === false ? false : date("Y-m-d H:i:s", $ts);
    }

    public static function downloadFile($fullPath, $fileName = '', $exit = true) {

        // Must be fresh start
        if (headers_sent())
            die('Headers Sent');

        // Required for some browsers
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        // File Exists?
        if (file_exists($fullPath)) {

            // Parse Info / Get Extension
            $fsize = filesize($fullPath);
            $path_parts = pathinfo($fullPath);
            $ext = strtolower($path_parts["extension"]);

            // Determine Content Type
            switch ($ext) {
                case "pdf":
                    $ctype = "application/pdf";
                    break;
                case "exe":
                    $ctype = "application/octet-stream";
                    break;
                case "zip":
                    $ctype = "application/zip";
                    break;
                case "doc":
                    $ctype = "application/msword";
                    break;
                case "xls":
                    $ctype = "application/vnd.ms-excel";
                    break;
                case "ppt":
                    $ctype = "application/vnd.ms-powerpoint";
                    break;
                case "gif":
                    $ctype = "image/gif";
                    break;
                case "png":
                    $ctype = "image/png";
                    break;
                case "jpeg":
                    $ctype = "image/jpg";
                    break;
                case "jpg":
                    $ctype = "image/jpg";
                    break;
                default:
                    $ctype = "application/force-download";
            }

            $fp = fopen($fullPath, 'rb');

            header("Pragma: public"); // required
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false); // required for certain browsers
            header("Content-Type: $ctype");
            header("Content-Disposition: attachment; filename=\"" . (($fileName) ? $fileName : basename($fullPath)) . "\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $fsize);
            ob_clean();
            flush();

            $begin = 0;
            $end = filesize($fullPath);
            $cur = $begin;
            fseek($fp, $begin, 0);
            while (!feof($fp) && $cur < $end && (connection_status() == 0)) {
                print fread($fp, min(1024 * 16, $end - $cur));
                $cur += 1024 * 16;
            }
            if ($exit) exit;
        } else die('File Not Found');
    }

    public static function compressCSS($css = '') {
        $bf = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $bf = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $bf);
        return preg_replace('/\s+/', ' ', $bf);
    }

    public static function EndsWith($Haystack, $Needle) {
        // Recommended version, using strpos
        return strrpos($Haystack, $Needle) === strlen($Haystack) - strlen($Needle);
    }

    public static function log($file = '', $string = '') {
        if (!$file) return false;
        $file = Director::baseFolder() . '/' . $file;
        $fp = fopen($file, 'a'); // 'a' will append or add to the file
        fwrite($fp, SECTION_DEVISION_LINE . R . 'NEW LOG ' . self::timestampToSQLDate(time()) . R . SECTION_DEVISION_LINE . R . $string . R . R);
        fclose($fp);
    }

    public static function isXHProfingEnabled() {
        return isset($_GET['xhprof']);
    }

    public static function xhprofStart() {
        if (self::isXHProfingEnabled() && extension_loaded('xhprof')) {
            xhprof_enable(XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
            return true;
        }
        return false;
    }

    public static function curPageURL() {
        return 'http' . ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? 's' : '') . '://'
        . $_SERVER["SERVER_NAME"] . (!in_array($_SERVER["SERVER_PORT"], array("80", "443")) ? ":" . $_SERVER["SERVER_PORT"] : '')
        . $_SERVER["REQUEST_URI"];
    }

    /**
     * @param string $str
     * @return string
     */
    public static function mb_trim($str) {
        return preg_replace("/(^\s+)|(\s+$)/us", "", $str);
    }

    public static function isDisplayCounters() {
        return $_SERVER['SERVER_ADDR'] == '95.67.121.131';
    }

    public static function dieWise($key = '') {
        die(\Utils\Qoutes::getRandomQoute($key));
    }

    public static function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public static function generateID() {
        return preg_replace('/\D/', '', '.' . microtime(true));
    }

    public static function is_array_assoc($array) {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    static $plural = array(
        '/(quiz)$/i' => "$1zes",
        '/^(ox)$/i' => "$1en",
        '/([m|l])ouse$/i' => "$1ice",
        '/(matr|vert|ind)ix|ex$/i' => "$1ices",
        '/(x|ch|ss|sh)$/i' => "$1es",
        '/([^aeiouy]|qu)y$/i' => "$1ies",
        '/(hive)$/i' => "$1s",
        '/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
        '/(shea|lea|loa|thie)f$/i' => "$1ves",
        '/sis$/i' => "ses",
        '/([ti])um$/i' => "$1a",
        '/(tomat|potat|ech|her|vet)o$/i' => "$1oes",
        '/(bu)s$/i' => "$1ses",
        '/(alias)$/i' => "$1es",
        '/(octop)us$/i' => "$1i",
        '/(ax|test)is$/i' => "$1es",
        '/(us)$/i' => "$1es",
        '/s$/i' => "s",
        '/$/' => "s"
    );

    static $singular = array(
        '/(quiz)zes$/i' => "$1",
        '/(matr)ices$/i' => "$1ix",
        '/(vert|ind)ices$/i' => "$1ex",
        '/^(ox)en$/i' => "$1",
        '/(alias)es$/i' => "$1",
        '/(octop|vir)i$/i' => "$1us",
        '/(cris|ax|test)es$/i' => "$1is",
        '/(shoe)s$/i' => "$1",
        '/(o)es$/i' => "$1",
        '/(bus)es$/i' => "$1",
        '/([m|l])ice$/i' => "$1ouse",
        '/(x|ch|ss|sh)es$/i' => "$1",
        '/(m)ovies$/i' => "$1ovie",
        '/(s)eries$/i' => "$1eries",
        '/([^aeiouy]|qu)ies$/i' => "$1y",
        '/([lr])ves$/i' => "$1f",
        '/(tive)s$/i' => "$1",
        '/(hive)s$/i' => "$1",
        '/(li|wi|kni)ves$/i' => "$1fe",
        '/(shea|loa|lea|thie)ves$/i' => "$1f",
        '/(^analy)ses$/i' => "$1sis",
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => "$1$2sis",
        '/([ti])a$/i' => "$1um",
        '/(n)ews$/i' => "$1ews",
        '/(h|bl)ouses$/i' => "$1ouse",
        '/(corpse)s$/i' => "$1",
        '/(us)es$/i' => "$1",
        '/s$/i' => ""
    );

    static $irregular = array(
        'move' => 'moves',
        'foot' => 'feet',
        'goose' => 'geese',
        'sex' => 'sexes',
        'child' => 'children',
        'man' => 'men',
        'tooth' => 'teeth',
        'person' => 'people',
        'valve' => 'valves'
    );

    static $uncountable = array(
        'sheep',
        'fish',
        'deer',
        'series',
        'species',
        'money',
        'rice',
        'information',
        'equipment'
    );

    public static function pluralize($string) {
        // save some time in the case that singular and plural are the same
        if (in_array(strtolower($string), self::$uncountable))
            return $string;


        // check for irregular singular forms
        foreach (self::$irregular as $pattern => $result) {
            $pattern = '/' . $pattern . '$/i';

            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach (self::$plural as $pattern => $result) {
            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        return $string;
    }

    public static function singularize($string) {
        // save some time in the case that singular and plural are the same
        if (in_array(strtolower($string), self::$uncountable))
            return $string;

        // check for irregular plural forms
        foreach (self::$irregular as $result => $pattern) {
            $pattern = '/' . $pattern . '$/i';

            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach (self::$singular as $pattern => $result) {
            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        return $string;
    }

    public static function pluralize_if($count, $string) {
        if ($count == 1)
            return "1 $string";
        else
            return $count . " " . self::pluralize($string);
    }

}


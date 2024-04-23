<?php

namespace Utils;

class UserAgent
{
    private static $bots=array(
        "AhrefsBot",
        "MJ12bot",
        "Apache-HttpClient",
        "Mail.RU_Bot",
        "news bot ",
        "SurveyBot ",
        "linkdexbot",
        "Exabot",
        "Blog Search",

        "Teoma",
        "alexa",
        "froogle",
        "inktomi",
        "looksmart",
        "URL_Spider_SQL",
        "Firefly",
        "NationalDirectory",
        "Ask Jeeves",
        "TECNOSEEK",
        "InfoSeek",
        "WebFindBot",
        "girafabot",
        "crawler",
        "Googlebot",
        "Scooter",
        "Slurp",
        "appie",
        "FAST",
        "WebBug",
        "Spade",
        "ZyBorg",

        "msnbot",
        "bingbot",

        "Yandex",
        "Yahoo",
        "YaDirectBot",
        "Google"
    );

    /**
     * @static
     * @return string
     */
    public static function isBot(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        foreach(self::$bots as $bot){
            if(strstr(strtolower($agent), strtolower($bot))) return $bot;
        }
        return '';
    }

}

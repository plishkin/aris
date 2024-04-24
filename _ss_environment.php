<?php

require_once(__DIR__ . '/mysite/code/Env.php');
Env::load();

global $_FILE_TO_URL_MAPPING;
$_FILE_TO_URL_MAPPING[__DIR__] = $_ENV['SS_BASE_URL'];

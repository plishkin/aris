<?php

global $project;
$project = 'mysite';

Env::load();

global $databaseConfig;
$databaseConfig = array(
	'type' => $_ENV['SS_DATABASE_CLASS'],
	'server' => $_ENV['SS_DATABASE_SERVER'],
	'username' => $_ENV['SS_DATABASE_USERNAME'],
	'password' => $_ENV['SS_DATABASE_PASSWORD'],
	'database' => $_ENV['SS_DATABASE_NAME'],
	'port' => $_ENV['SS_DATABASE_PORT'],
);

Config::inst()->update('Director', 'environment_type', "dev");// live dev


ini_set('display_errors',1);
ini_set('html_errors','On');
error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE);// E_ALL E_ERROR
ini_set('max_execution_time', 180);

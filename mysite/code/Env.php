<?php

class Env
{

	private static $env = [];
	public static function load()
	{
		$lines = file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		foreach ($lines as $line) {

			if (strpos(trim($line), '#') === 0) {
				continue;
			}

			list($name, $value) = explode('=', $line, 2);
			$name = trim($name);
			$value = trim($value);
			$value = trim($value, '\'"');

			if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
				putenv(sprintf('%s=%s', $name, $value));
				$_ENV[$name] = $value;
				$_SERVER[$name] = $value;
			}
			if (strpos($name, 'SS_') === 0 && !defined($name)) {
				define($name, $value);
			}
		}
	}

}

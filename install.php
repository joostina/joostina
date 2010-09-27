<?php

if ( ! preg_match('/^.$/u', 'ñ')) {
	die
			(
			'<a href="http://php.net/pcre">PCRE</a> has not been compiled with UTF-8 support. '.
			'See <a href="http://php.net/manual/reference.pcre.pattern.modifiers.php">PCRE Pattern Modifiers</a> '.
			'for more information. This application cannot be run without UTF-8 support.'
	);
}

if ( ! extension_loaded('iconv')) {
	die
			(
			'The <a href="http://php.net/iconv">iconv</a> extension is not loaded. '.
			'Without iconv, strings cannot be properly translated to UTF-8 from user input. '.
			'This application cannot be run without UTF-8 support.'
	);
}

if (extension_loaded('mbstring') AND (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING)) {
	die
			(
			'The <a href="http://php.net/mbstring">mbstring</a> extension is overloading PHP\'s native string functions. '.
			'Disable this by setting mbstring.func_overload to 0, 1, 4 or 5 in php.ini or a .htaccess file.'
	);
}

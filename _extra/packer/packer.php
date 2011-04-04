<?php

$root = dirname(__DIR__);

$files = array(
	'\includes\route.php',
	'\includes\extraroute.php',
	'\includes\joostina.php',
	'\includes\libraries\debug\debug.php',
	'\includes\libraries\jstring\jstring.php',
	'\includes\libraries\inputfilter\inputfilter.php',
	'\includes\libraries\database\database.php',
	'\components\users\users.class.php',
	'\includes\frontend.php',
	'\includes\libraries\html\html.php',
	'\language\russian\system.php',
);

$f = array();
$new_code = array();

foreach ($files as $file) {
	$tokens = token_get_all(file_get_contents( $root . $file));
	foreach ($tokens as $token) {
		if (is_array($token)) {
			if (!in_array(token_name($token[0]), array('T_DOC_COMMENT', 'T_COMMENT', 'T_WHITESPACE','T_OPEN_TAG','T_CLOSE_TAG'))) {
				$f[] = token_name($token[0]) . ': ' . $token[1];
				$new_code[] = ' ' . $token[1];
			}
		} else {
			$f[] = $token;
			$new_code[] = $token;
		}
	}
	$new_code[] = "\n";
}


$new_code = str_replace('; ', ';', implode('', $new_code));

$new_code = '<?php '.$new_code.' ?>';

file_put_contents( $root.'/packer/pack/pack.php', $new_code);
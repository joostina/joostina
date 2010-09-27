<?php
/**
 * Tries to detect if a string is in Unicode encoding
 * :NOTE:
 * Функция работает медленнее, чем рег. выражение is_utf8()
 *
 * @param    string   $str          текст
 * @param    bool     $is_strict    строгая проверка диапазона ASCII?
 *
 * @link     http://www.php.net/manual/en/function.utf8-encode.php
 *
 * @license  http://creativecommons.org/licenses/by-sa/3.0/
 * @author   <bmorel at ssi dot fr>
 * @author   Nasibullin Rinat <nasibullin at starlink ru> (small changes)
 * @charset  ANSI
 * @version  1.0.3
 */
function utf8_check($str, $is_strict = true) {
	for($i = 0, $len = strlen($str); $i < $len; $i++) {
		$c = ord($str[$i]);
		if($c < 0x80) #1 byte  0bbbbbbb
			{
			if($is_strict === false || ($c > 0x1F && $c < 0x7F) || $c == 0x09 || $c == 0x0A || $c == 0x0D) continue;
		}
		if(($c & 0xE0) == 0xC0) $n = 1; #2 bytes 110bbbbb 10bbbbbb
		elseif(($c & 0xF0) == 0xE0) $n = 2; #3 bytes 1110bbbb 10bbbbbb 10bbbbbb
		elseif(($c & 0xF8) == 0xF0) $n = 3; #4 bytes 11110bbb 10bbbbbb 10bbbbbb 10bbbbbb
		elseif(($c & 0xFC) == 0xF8) $n = 4; #5 bytes 111110bb 10bbbbbb 10bbbbbb 10bbbbbb 10bbbbbb
		elseif(($c & 0xFE) == 0xFC) $n = 5; #6 bytes 1111110b 10bbbbbb 10bbbbbb 10bbbbbb 10bbbbbb 10bbbbbb
		else  return false; #does not match any model
		#n bytes matching 10bbbbbb follow ?
		for($j = 0; $j < $n; $j++) {
			$i++;
			if($i == $len || ((ord($str[$i]) & 0xC0) != 0x80)) return false;
		} #for
	} #for
	return true;
}
?>

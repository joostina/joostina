<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
/**
 * Базируется н акласа UTF-8 (c) Kohana Team
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007 Kohana Team
 * @copyright  (c) 2005 Harry Fuecks
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class joosString {

	// Called methods
	static $called = array();

	/**
	 * Tests whether a string contains only 7bit ASCII bytes. This is used to
	 * determine when to use native functions or UTF-8 functions.
	 *
	 * @param   string  string to check
	 * @return  bool
	 */
	public static function is_ascii($str) {
		return!preg_match('/[^\x00-\x7F]/S', $str);
	}

	/**
	 * Strips out device control codes in the ASCII range.
	 *
	 * @param   string  string to clean
	 * @return  string
	 */
	public static function strip_ascii_ctrl($str) {
		return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $str);
	}

	/**
	 * Strips out all non-7bit ASCII bytes.
	 *
	 * @param   string  string to clean
	 * @return  string
	 */
	public static function strip_non_ascii($str) {
		return preg_replace('/[^\x00-\x7F]+/S', '', $str);
	}

	/**
	 * Replaces special/accented UTF-8 characters by ASCII-7 'equivalents'.
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 *
	 * @param   string   string to transliterate
	 * @param   integer  -1 lowercase only, +1 uppercase only, 0 both cases
	 * @return  string
	 */
	public static function transliterate_to_ascii($str, $case = 0) {
		if (!isset(self::$called[__FUNCTION__])) {
			require SYSPATH . __FUNCTION__ . EXT;

			// Function has been called
			self::$called[__FUNCTION__] = TRUE;
		}

		return _transliterate_to_ascii($str, $case);
	}

	/**
	 * Returns the length of the given string.
	 * @see http://php.net/strlen
	 *
	 * @param   string   string being measured for length
	 * @return  integer
	 */
	public static function strlen($str) {
		return mb_strlen($str, 'utf-8');
	}

	/**
	 * Finds position of first occurrence of a UTF-8 string.
	 * @see http://php.net/strlen
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   haystack
	 * @param   string   needle
	 * @param   integer  offset from which character in haystack to start searching
	 * @return  integer  position of needle
	 * @return  boolean  FALSE if the needle is not found
	 */
	public static function strpos($str, $search, $offset = 0) {
		return mb_strpos($str, $search, $offset, 'UTF-8');
	}

	/**
	 * Finds position of last occurrence of a char in a UTF-8 string.
	 * @see http://php.net/strrpos
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   haystack
	 * @param   string   needle
	 * @param   integer  offset from which character in haystack to start searching
	 * @return  integer  position of needle
	 * @return  boolean  FALSE if the needle is not found
	 */
	public static function strrpos($str, $search, $offset = 0) {
		return mb_strrpos($str, $search, $offset, 'utf-8');
	}

	/**
	 * Returns part of a UTF-8 string.
	 * @see http://php.net/substr
	 *
	 * @author  Chris Smith <chris@jalakai.co.uk>
	 *
	 * @param   string   input string
	 * @param   integer  offset
	 * @param   integer  length limit
	 * @return  string
	 */
	public static function substr($str, $offset, $length = NULL) {
		return ($length === NULL) ? mb_substr($str, $offset, null, 'UTF-8') : mb_substr($str, $offset, $length, 'UTF-8');
	}

	/**
	 * Replaces text within a portion of a UTF-8 string.
	 * @see http://php.net/substr_replace
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   input string
	 * @param   string   replacement string
	 * @param   integer  offset
	 * @return  string
	 */
	public static function substr_replace($str, $replacement, $offset, $length = NULL) {
		return ($length === NULL) ? substr_replace($str, $replacement, $offset) : substr_replace($str, $replacement, $offset, $length);
	}

	/**
	 * Makes a UTF-8 string lowercase.
	 * @see http://php.net/strtolower
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 *
	 * @param   string   mixed case string
	 * @return  string
	 */
	public static function strtolower($str) {
		return mb_strtolower($str, 'UTF-8');
	}

	/**
	 * Makes a UTF-8 string uppercase.
	 * @see http://php.net/strtoupper
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 *
	 * @param   string   mixed case string
	 * @return  string
	 */
	public static function strtoupper($str) {
		return mb_strtoupper($str, 'UTF-8');
	}

	/**
	 * Makes a UTF-8 string's first character uppercase.
	 * @see http://php.net/ucfirst
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   mixed case string
	 * @return  string
	 */
	public static function ucfirst($str) {
		return mb_strtolower(mb_substr($str, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($str, 1, mb_strlen($str, 'UTF-8'), 'UTF-8');
	}

	/**
	 * Makes the first character of every word in a UTF-8 string uppercase.
	 * @see http://php.net/ucwords
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   mixed case string
	 * @return  string
	 */
	public static function ucwords($str) {
		return mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
	}

	/**
	 * Case-insensitive UTF-8 string comparison.
	 * @see http://php.net/strcasecmp
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   string to compare
	 * @param   string   string to compare
	 * @return  integer  less than 0 if str1 is less than str2
	 * @return  integer  greater than 0 if str1 is greater than str2
	 * @return  integer  0 if they are equal
	 */
	public static function strcasecmp($str1, $str2) {
		$str1 = mb_strtolower($str1, 'UTF-8');
		$str2 = mb_strtolower($str2, 'UTF-8');
		return strcmp($str1, $str2);
	}

	/**
	 * Returns a string or an array with all occurrences of search in subject (ignoring case).
	 * replaced with the given replace value.
	 * @see     http://php.net/str_ireplace
	 *
	 * @note    It's not fast and gets slower if $search and/or $replace are arrays.
	 * @author  Harry Fuecks <hfuecks@gmail.com
	 *
	 * @param   string|array  text to replace
	 * @param   string|array  replacement text
	 * @param   string|array  subject text
	 * @param   integer       number of matched and replaced needles will be returned via this parameter which is passed by reference
	 * @return  string        if the input was a string
	 * @return  array         if the input was an array
	 */
	public static function str_ireplace($search, $replace, $str, & $count = NULL) {

		if (!is_array($search)) {

			$slen = strlen($search);
			$lendif = strlen($replace) - $slen;
			if ($slen == 0) {
				return $str;
			}

			$search = self::strtolower($search);

			$search = preg_quote($search, '/');
			$lstr = self::strtolower($str);
			$i = 0;
			$matched = 0;
			while (preg_match('/(.*)' . $search . '/Us', $lstr, $matches)) {
				if ($i === $count) {
					break;
				}
				$mlen = strlen($matches[0]);
				$lstr = substr($lstr, $mlen);
				$str = substr_replace($str, $replace, $matched + strlen($matches[1]), $slen);
				$matched += $mlen + $lendif;
				$i++;
			}
			return $str;
		} else {

			foreach (array_keys($search) as $k) {

				if (is_array($replace)) {

					if (array_key_exists($k, $replace)) {

						$str = self::str_ireplace($search[$k], $replace[$k], $str, $count);
					} else {

						$str = self::str_ireplace($search[$k], '', $str, $count);
					}
				} else {

					$str = utf8_ireplace($search[$k], $replace, $str, $count);
				}
			}
			return $str;
		}
	}

	/**
	 * Case-insenstive UTF-8 version of strstr. Returns all of input string
	 * from the first occurrence of needle to the end.
	 * @see http://php.net/stristr
	 *
	 * @author Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   input string
	 * @param   string   needle
	 * @return  string   matched substring if found
	 * @return  boolean  FALSE if the substring was not found
	 */
	public static function stristr($str, $search) {
		if ($search == '')
			return $str;

		$str_lower = self::strtolower($str);
		$search_lower = self::strtolower($search);

		preg_match('/^(.*?)' . preg_quote($search, '/') . '/s', $str_lower, $matches);

		if (isset($matches[1]))
			return substr($str, strlen($matches[1]));

		return FALSE;
	}

	/**
	 * Finds the length of the initial segment matching mask.
	 * @see http://php.net/strspn
	 *
	 * @author Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   input string
	 * @param   string   mask for search
	 * @param   integer  start position of the string to examine
	 * @param   integer  length of the string to examine
	 * @return  integer  length of the initial segment that contains characters in the mask
	 */
	public static function strspn($str, $mask, $offset = NULL, $length = NULL) {
		if ($str == '' OR $mask == '')
			return 0;

		if ($offset !== NULL OR $length !== NULL) {
			$str = self::substr($str, $offset, $length);
		}

		// Escape these characters:  - [ ] . : \ ^ /
		// The . and : are escaped to prevent possible warnings about POSIX regex elements
		$mask = preg_replace('#[-[\].:\\\\^/]#', '\\\\$0', $mask);
		preg_match('/^[^' . $mask . ']+/u', $str, $matches);

		return isset($matches[0]) ? self::strlen($matches[0]) : 0;
	}

	/**
	 * Finds the length of the initial segment not matching mask.
	 * @see http://php.net/strcspn
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   input string
	 * @param   string   mask for search
	 * @param   integer  start position of the string to examine
	 * @param   integer  length of the string to examine
	 * @return  integer  length of the initial segment that contains characters not in the mask
	 */
	public static function strcspn($str, $mask, $offset = NULL, $length = NULL) {
		if ($str == '' OR $mask == '')
			return 0;

		if ($str !== NULL OR $length !== NULL) {
			$str = self::substr($str, $offset, $length);
		}

		// Escape these characters:  - [ ] . : \ ^ /
		// The . and : are escaped to prevent possible warnings about POSIX regex elements
		$mask = preg_replace('#[-[\].:\\\\^/]#', '\\\\$0', $mask);
		preg_match('/^[^' . $mask . ']+/u', $str, $matches);

		return isset($matches[0]) ? self::strlen($matches[0]) : 0;
	}

	/**
	 * Pads a UTF-8 string to a certain length with another string.
	 * @see http://php.net/str_pad
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   input string
	 * @param   integer  desired string length after padding
	 * @param   string   string to use as padding
	 * @param   string   padding type: STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
	 * @return  string
	 */
	public static function str_pad($str, $final_str_length, $pad_str = ' ', $pad_type = STR_PAD_RIGHT) {

		$str_length = self::strlen($str);

		if ($final_str_length <= 0 OR $final_str_length <= $str_length) {
			return $str;
		}

		$pad_str_length = self::strlen($pad_str);
		$pad_length = $final_str_length - $str_length;

		if ($pad_type == STR_PAD_RIGHT) {
			$repeat = ceil($pad_length / $pad_str_length);
			return self::substr($str . str_repeat($pad_str, $repeat), 0, $final_str_length);
		}

		if ($pad_type == STR_PAD_LEFT) {
			$repeat = ceil($pad_length / $pad_str_length);
			return self::substr(str_repeat($pad_str, $repeat), 0, floor($pad_length)) . $str;
		}

		if ($pad_type == STR_PAD_BOTH) {
			$pad_length /= 2;
			$pad_length_left = floor($pad_length);
			$pad_length_right = ceil($pad_length);
			$repeat_left = ceil($pad_length_left / $pad_str_length);
			$repeat_right = ceil($pad_length_right / $pad_str_length);

			$pad_left = self::substr(str_repeat($pad_str, $repeat_left), 0, $pad_length_left);
			$pad_right = self::substr(str_repeat($pad_str, $repeat_right), 0, $pad_length_left);
			return $pad_left . $str . $pad_right;
		}
	}

	/**
	 * Converts a UTF-8 string to an array.
	 * @see http://php.net/str_split
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   input string
	 * @param   integer  maximum length of each chunk
	 * @return  array
	 */
	public static function str_split($str, $split_length = 1) {
		$split_length = (int) $split_length;

		if ($split_length < 1) {
			return FALSE;
		}

		if (self::strlen($str) <= $split_length) {
			return array($str);
		}

		preg_match_all('/.{' . $split_length . '}|[^\x00]{1,' . $split_length . '}$/us', $str, $matches);

		return $matches[0];
	}

	/**
	 * Reverses a UTF-8 string.
	 * @see http://php.net/strrev
	 *
	 * @author  Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   string to be reversed
	 * @return  string
	 */
	public static function strrev($str) {
		preg_match_all('/./us', $str, $matches);
		return implode('', array_reverse($matches[0]));
	}

	/**
	 * Strips whitespace (or other UTF-8 characters) from the beginning and
	 * end of a string.
	 * @see http://php.net/trim
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 *
	 * @param   string   input string
	 * @param   string   string of characters to remove
	 * @return  string
	 */
	public static function trim($str, $charlist = NULL) {
		if ($charlist === NULL) {
			return trim($str);
		}

		return self::ltrim(self::rtrim($str, $charlist), $charlist);
	}

	/**
	 * Strips whitespace (or other UTF-8 characters) from the beginning of a string.
	 * @see http://php.net/ltrim
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 *
	 * @param   string   input string
	 * @param   string   string of characters to remove
	 * @return  string
	 */
	public static function ltrim($str, $charlist = NULL) {
		if ($charlist === NULL) {
			return ltrim($str);
		}

		$charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

		return preg_replace('/^[' . $charlist . ']+/u', '', $str);
	}

	/**
	 * Strips whitespace (or other UTF-8 characters) from the end of a string.
	 * @see http://php.net/rtrim
	 *
	 * @author  Andreas Gohr <andi@splitbrain.org>
	 *
	 * @param   string   input string
	 * @param   string   string of characters to remove
	 * @return  string
	 */
	public static function rtrim($str, $charlist = NULL) {
		if ($charlist === NULL) {
			return rtrim($str);
		}

		$charlist = preg_replace('#[-\[\]:\\\\^/]#', '\\\\$0', $charlist);

		return preg_replace('/[' . $charlist . ']++$/uD', '', $str);
	}

	/**
	 * Returns the unicode ordinal for a character.
	 * @see http://php.net/ord
	 *
	 * @author Harry Fuecks <hfuecks@gmail.com>
	 *
	 * @param   string   UTF-8 encoded character
	 * @return  integer
	 */
	public static function ord($chr) {
		$ord0 = ord($chr);

		if ($ord0 >= 0 AND $ord0 <= 127) {
			return $ord0;
		}

		if (!isset($chr[1])) {
			trigger_error('Short sequence - at least 2 bytes expected, only 1 seen', E_USER_WARNING);
			return FALSE;
		}

		$ord1 = ord($chr[1]);

		if ($ord0 >= 192 AND $ord0 <= 223) {
			return ($ord0 - 192) * 64 + ($ord1 - 128);
		}

		if (!isset($chr[2])) {
			trigger_error('Short sequence - at least 3 bytes expected, only 2 seen', E_USER_WARNING);
			return FALSE;
		}

		$ord2 = ord($chr[2]);

		if ($ord0 >= 224 AND $ord0 <= 239) {
			return ($ord0 - 224) * 4096 + ($ord1 - 128) * 64 + ($ord2 - 128);
		}

		if (!isset($chr[3])) {
			trigger_error('Short sequence - at least 4 bytes expected, only 3 seen', E_USER_WARNING);
			return FALSE;
		}

		$ord3 = ord($chr[3]);

		if ($ord0 >= 240 AND $ord0 <= 247) {
			return ($ord0 - 240) * 262144 + ($ord1 - 128) * 4096 + ($ord2 - 128) * 64 + ($ord3 - 128);
		}

		if (!isset($chr[4])) {
			trigger_error('Short sequence - at least 5 bytes expected, only 4 seen', E_USER_WARNING);
			return FALSE;
		}

		$ord4 = ord($chr[4]);

		if ($ord0 >= 248 AND $ord0 <= 251) {
			return ($ord0 - 248) * 16777216 + ($ord1 - 128) * 262144 + ($ord2 - 128) * 4096 + ($ord3 - 128) * 64 + ($ord4 - 128);
		}

		if (!isset($chr[5])) {
			trigger_error('Short sequence - at least 6 bytes expected, only 5 seen', E_USER_WARNING);
			return FALSE;
		}

		if ($ord0 >= 252 AND $ord0 <= 253) {
			return ($ord0 - 252) * 1073741824 + ($ord1 - 128) * 16777216 + ($ord2 - 128) * 262144 + ($ord3 - 128) * 4096 + ($ord4 - 128) * 64 + (ord($chr[5]) - 128);
		}

		if ($ord0 >= 254 AND $ord0 <= 255) {
			trigger_error('Invalid UTF-8 with surrogate ordinal ' . $ord0, E_USER_WARNING);
			return FALSE;
		}
	}

	/**
	 * Takes an UTF-8 string and returns an array of ints representing the Unicode characters.
	 * Astral planes are supported i.e. the ints in the output can be > 0xFFFF.
	 * Occurrances of the BOM are ignored. Surrogates are not allowed.
	 *
	 * The Original Code is Mozilla Communicator client code.
	 * The Initial Developer of the Original Code is Netscape Communications Corporation.
	 * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
	 * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see http://hsivonen.iki.fi/php-utf8/.
	 * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>.
	 *
	 * @param   string   UTF-8 encoded string
	 * @return  array    unicode code points
	 * @return  boolean  FALSE if the string is invalid
	 */
	public static function to_unicode($str) {
		$mState = 0; // cached expected number of octets after the current octet until the beginning of the next UTF8 character sequence
		$mUcs4 = 0; // cached Unicode character
		$mBytes = 1; // cached expected number of octets in the current sequence

		$out = array();

		$len = strlen($str);

		for ($i = 0; $i < $len; $i++) {
			$in = ord($str[$i]);

			if ($mState == 0) {
				// When mState is zero we expect either a US-ASCII character or a
				// multi-octet sequence.
				if (0 == (0x80 & $in)) {
					// US-ASCII, pass straight through.
					$out[] = $in;
					$mBytes = 1;
				} elseif (0xC0 == (0xE0 & $in)) {
					// First octet of 2 octet sequence
					$mUcs4 = $in;
					$mUcs4 = ($mUcs4 & 0x1F) << 6;
					$mState = 1;
					$mBytes = 2;
				} elseif (0xE0 == (0xF0 & $in)) {
					// First octet of 3 octet sequence
					$mUcs4 = $in;
					$mUcs4 = ($mUcs4 & 0x0F) << 12;
					$mState = 2;
					$mBytes = 3;
				} elseif (0xF0 == (0xF8 & $in)) {
					// First octet of 4 octet sequence
					$mUcs4 = $in;
					$mUcs4 = ($mUcs4 & 0x07) << 18;
					$mState = 3;
					$mBytes = 4;
				} elseif (0xF8 == (0xFC & $in)) {
					// First octet of 5 octet sequence.
					//
                    // This is illegal because the encoded codepoint must be either
					// (a) not the shortest form or
					// (b) outside the Unicode range of 0-0x10FFFF.
					// Rather than trying to resynchronize, we will carry on until the end
					// of the sequence and let the later error handling code catch it.
					$mUcs4 = $in;
					$mUcs4 = ($mUcs4 & 0x03) << 24;
					$mState = 4;
					$mBytes = 5;
				} elseif (0xFC == (0xFE & $in)) {
					// First octet of 6 octet sequence, see comments for 5 octet sequence.
					$mUcs4 = $in;
					$mUcs4 = ($mUcs4 & 1) << 30;
					$mState = 5;
					$mBytes = 6;
				} else {
					// Current octet is neither in the US-ASCII range nor a legal first octet of a multi-octet sequence.
					trigger_error('utf8::to_unicode: Illegal sequence identifier in UTF-8 at byte ' . $i, E_USER_WARNING);
					return FALSE;
				}
			} else {
				// When mState is non-zero, we expect a continuation of the multi-octet sequence
				if (0x80 == (0xC0 & $in)) {
					// Legal continuation
					$shift = ($mState - 1) * 6;
					$tmp = $in;
					$tmp = ($tmp & 0x0000003F) << $shift;
					$mUcs4 |= $tmp;

					// End of the multi-octet sequence. mUcs4 now contains the final Unicode codepoint to be output
					if (0 == --$mState) {
						// Check for illegal sequences and codepoints
						// From Unicode 3.1, non-shortest form is illegal
						if (((2 == $mBytes) AND ($mUcs4 < 0x0080)) OR
								((3 == $mBytes) AND ($mUcs4 < 0x0800)) OR
								((4 == $mBytes) AND ($mUcs4 < 0x10000)) OR
								(4 < $mBytes) OR
								// From Unicode 3.2, surrogate characters are illegal
								(($mUcs4 & 0xFFFFF800) == 0xD800) OR
								// Codepoints outside the Unicode range are illegal
								($mUcs4 > 0x10FFFF)) {
							trigger_error('utf8::to_unicode: Illegal sequence or codepoint in UTF-8 at byte ' . $i, E_USER_WARNING);
							return FALSE;
						}

						if (0xFEFF != $mUcs4) {
							// BOM is legal but we don't want to output it
							$out[] = $mUcs4;
						}

						// Initialize UTF-8 cache
						$mState = 0;
						$mUcs4 = 0;
						$mBytes = 1;
					}
				} else {
					// ((0xC0 & (*in) != 0x80) AND (mState != 0))
					// Incomplete multi-octet sequence
					trigger_error('joosString::to_unicode: Incomplete multi-octet sequence in UTF-8 at byte ' . $i, E_USER_WARNING);
					return FALSE;
				}
			}
		}

		return $out;
	}

	/**
	 * Takes an array of ints representing the Unicode characters and returns a UTF-8 string.
	 * Astral planes are supported i.e. the ints in the input can be > 0xFFFF.
	 * Occurrances of the BOM are ignored. Surrogates are not allowed.
	 *
	 * The Original Code is Mozilla Communicator client code.
	 * The Initial Developer of the Original Code is Netscape Communications Corporation.
	 * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer.
	 * Ported to PHP by Henri Sivonen <hsivonen@iki.fi>, see http://hsivonen.iki.fi/php-utf8/.
	 * Slight modifications to fit with phputf8 library by Harry Fuecks <hfuecks@gmail.com>.
	 *
	 * @param   array    unicode code points representing a string
	 * @return  string   utf8 string of characters
	 * @return  boolean  FALSE if a code point cannot be found
	 */
	public static function from_unicode($arr) {
		ob_start();

		$keys = array_keys($arr);

		foreach ($keys as $k) {
			// ASCII range (including control chars)
			if (($arr[$k] >= 0) AND ($arr[$k] <= 0x007f)) {
				echo chr($arr[$k]);
			}
			// 2 byte sequence
			elseif ($arr[$k] <= 0x07ff) {
				echo chr(0xc0 | ($arr[$k] >> 6));
				echo chr(0x80 | ($arr[$k] & 0x003f));
			}
			// Byte order mark (skip)
			elseif ($arr[$k] == 0xFEFF) {
				// nop -- zap the BOM
			}
			// Test for illegal surrogates
			elseif ($arr[$k] >= 0xD800 AND $arr[$k] <= 0xDFFF) {
				// Found a surrogate
				trigger_error('utf8::from_unicode: Illegal surrogate at index: ' . $k . ', value: ' . $arr[$k], E_USER_WARNING);
				return FALSE;
			}
			// 3 byte sequence
			elseif ($arr[$k] <= 0xffff) {
				echo chr(0xe0 | ($arr[$k] >> 12));
				echo chr(0x80 | (($arr[$k] >> 6) & 0x003f));
				echo chr(0x80 | ($arr[$k] & 0x003f));
			}
			// 4 byte sequence
			elseif ($arr[$k] <= 0x10ffff) {
				echo chr(0xf0 | ($arr[$k] >> 18));
				echo chr(0x80 | (($arr[$k] >> 12) & 0x3f));
				echo chr(0x80 | (($arr[$k] >> 6) & 0x3f));
				echo chr(0x80 | ($arr[$k] & 0x3f));
			}
			// Out of range
			else {
				trigger_error('utf8::from_unicode: Codepoint out of Unicode range at index: ' . $k . ', value: ' . $arr[$k], E_USER_WARNING);
				return FALSE;
			}
		}

		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

	public static function to_utf8(&$text) {
		if (is_array($text) OR is_object($text)) {
			$d = array();
			foreach ($text as $k => &$v) {
				$d[self::to_utf8($k)] = self::to_utf8($v);
			}
			return $d;
		}
		if (is_string($text)) {
			if (self::is_utf8($text)) { // если это юникод - сразу его возвращаем
				return $text;
			}
			if (function_exists('iconv')) { // пробуем конвертировать через iconv
				return iconv('cp1251', 'utf-8//IGNORE//TRANSLIT', $text);
			}

			if (!function_exists('cp1259_to_utf8')) { // конвертируем собственнвми средствами
				include_once JPATH_BASE . '/includes/libraries/utf8/to_utf8.php';
			}
			return cp1259_to_utf8($text);
		}
		return $text;
	}

	/* проверка на юникод */

	public static function is_utf8(&$data, $is_strict = true) {
		if (is_array($data)) { // массив
			foreach ($data as $k => &$v) {
				if (!self::is_utf8($v, $is_strict)) {
					return false;
				}
			}
			return true;
		} elseif (is_string($data)) { // строка
			if (function_exists('iconv')) {
				$distance = strlen($data) - strlen(iconv('UTF-8', 'UTF-8//IGNORE', $data));
				if ($distance > 0) {
					return false;
				}
				if ($is_strict && preg_match('/[^\x09\x0A\x0D\x20-\xFF]/sS', $data)) {
					return false;
				}
				return true;
			}

			return self::utf8_check($data, $is_strict);
		} elseif (is_scalar($data) || is_null($data)) { //числа, булево и ничего
			return true;
		}
		return false;
	}

	/* проверка на юникод */

	public static function utf8_check($str, $is_strict = true) {
		for ($i = 0, $len = strlen($str); $i < $len; $i++) {
			$c = ord($str[$i]);
			if ($c < 0x80) {
				if ($is_strict === false || ($c > 0x1F && $c < 0x7F) || $c == 0x09 || $c == 0x0A || $c == 0x0D)
					continue;
			}
			if (($c & 0xE0) == 0xC0)
				$n = 1;
			elseif (($c & 0xF0) == 0xE0)
				$n = 2;
			elseif (($c & 0xF8) == 0xF0)
				$n = 3;
			elseif (($c & 0xFC) == 0xF8)
				$n = 4;
			elseif (($c & 0xFE) == 0xFC)
				$n = 5;
			else
				return false;
			for ($j = 0; $j < $n; $j++) {
				$i++;
				if ($i == $len || ((ord($str[$i]) & 0xC0) != 0x80))
					return false;
			}
		}
		return true;
	}

}

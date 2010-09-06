<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

class Text {

	public static $abc_ru = array(
		'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я'
	);
	public static $abc_en = array(
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
	);

	/**
	 * Вывод численных результатов с учетом склонения слов
	 * @access public
	 * @param integer $int
	 * @param array $expressions Например: array("ответ", "ответа", "ответов")
	 */
	public static function declension($int, $expressions) {
		if (count($expressions) < 3) {
			$expressions[2] = $expressions[1];
		};
		settype($int, 'integer');
		$count = $int % 100;
		if ($count >= 5 && $count <= 20) {
			$result = $expressions['2'];
		} else {
			$count = $count % 10;
			if ($count == 1) {
				$result = $expressions['0'];
			} elseif ($count >= 2 && $count <= 4) {
				$result = $expressions['1'];
			} else {
				$result = $expressions['2'];
			}
		}
		return $result;
	}

	/**
	 * Word Limiter
	 *
	 * Limits a string to X number of words.
	 *
	 * @access	public
	 * @param	string
	 * @param	integer
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */
	public static function word_limiter($str, $limit = 100, $end_char = '&#8230;') {
		if (Jstring::trim($str) == '') {
			return $str;
		}

		preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/u', $str, $matches);

		if (Jstring::strlen($str) == Jstring::strlen($matches[0])) {
			$end_char = '';
		}

		return Jstring::rtrim($matches[0]) . $end_char;
	}

	/**
	 * Character Limiter
	 *
	 * Limits the string based on the character count.  Preserves complete words
	 * so the character count may not be exactly as specified.
	 *
	 * @access	public
	 * @param	string
	 * @param	integer
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */
	public static function character_limiter($str, $n = 500, $end_char = '&#8230;') {
		if (strlen($str) < $n) {
			return $str;
		}

		$str = preg_replace("/\s+/u", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if (Jstring::strlen($str) <= $n) {
			return $str;
		}

		$out = "";
		foreach (explode(' ', Jstring::trim($str)) as $val) {
			$out .= $val . ' ';

			if (Jstring::strlen($out) >= $n) {
				$out = Jstring::trim($out);
				return (Jstring::strlen($out) == Jstring::strlen($str)) ? $out : $out . $end_char;
			}
		}
	}

	/**
	 * Word Censoring Function
	 *
	 * Supply a string and an array of disallowed words and any
	 * matched words will be converted to #### or to the replacement
	 * word you've submitted.
	 *
	 * @access	public
	 * @param	string	the text string
	 * @param	string	the array of censoered words
	 * @param	string	the optional replacement value
	 * @return	string
	 */
	public static function word_censor($str, $censored, $replacement = '') {
		if (!is_array($censored)) {
			return $str;
		}

		$str = ' ' . $str . ' ';

		// \w, \b and a few others do not match on a unicode character
		// set for performance reasons. As a result words like uber
		// will not match on a word boundary. Instead, we'll assume that
		// a bad word will be bookended by any of these characters.
		$delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

		foreach ($censored as $badword) {
			if ($replacement != '') {
				$str = preg_replace("/({$delim})(" . str_replace('\*', '\w*?', preg_quote($badword, '/')) . ")({$delim})/i", "\\1{$replacement}\\3", $str);
			} else {
				$str = preg_replace("/({$delim})(" . str_replace('\*', '\w*?', preg_quote($badword, '/')) . ")({$delim})/ie", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
			}
		}

		return trim($str);
	}

	/**
	 * Более продвинутый аналог strip_tags() для корректного вырезания тагов из html кода.
	 * Функция strip_tags(), в зависимости от контекста, может работать не корректно.
	 * Возможности:
	 *   - корректно обрабатываются вхождения типа "a < b > c"
	 *   - корректно обрабатывается "грязный" html, когда в значениях атрибутов тагов могут встречаться символы < >
	 *   - корректно обрабатывается разбитый html
	 *   - вырезаются комментарии, скрипты, стили, PHP, Perl, ASP код, MS Word таги, CDATA
	 *   - автоматически форматируется текст, если он содержит html код
	 *   - защита от подделок типа: "<<fake>script>alert('hi')</</fake>script>"
	 *
	 * @param   string  $s
	 * @param   array   $allowable_tags	 Массив тагов, которые не будут вырезаны
	 * 									  Пример: 'b' -- таг останется с атрибутами, '<b>' -- таг останется без атрибутов
	 * @param   bool	$is_format_spaces   Форматировать пробелы и переносы строк?
	 * 									  Вид текста на выходе (plain) максимально приближеется виду текста в браузере на входе.
	 * 									  Другими словами, грамотно преобразует text/html в text/plain.
	 * 									  Текст форматируется только в том случае, если были вырезаны какие-либо таги.
	 * @param   array   $pair_tags   массив имён парных тагов, которые будут удалены вместе с содержимым
	 * 							   см. значения по умолчанию
	 * @param   array   $para_tags   массив имён парных тагов, которые будут восприниматься как параграфы (если $is_format_spaces = true)
	 * 							   см. значения по умолчанию
	 * @return  string
	 *
	 * @license  http://creativecommons.org/licenses/by-sa/3.0/
	 * @author   Nasibullin Rinat, http://orangetie.ru/
	 * @charset  ANSI
	 * @version  4.0.14
	 */
	public static function strip_tags_smart(
	/* string */ $s, array $allowable_tags = null,
	/* boolean */  $is_format_spaces = true, array $pair_tags = array('script', 'style', 'map', 'iframe', 'frameset', 'object', 'applet', 'comment', 'button', 'textarea', 'select'), array $para_tags = array('p', 'td', 'th', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'form', 'title', 'pre')
	) {
		static $_callback_type = false;
		static $_allowable_tags = array();
		static $_para_tags = array();
		static $re_attrs_fast_safe = '(?![a-zA-Z\d])  #statement, which follows after a tag
									   #correct attributes
									   (?>
										   [^>"\']+
										 | (?<=[\=\x20\r\n\t]|\xc2\xa0) "[^"]*"
										 | (?<=[\=\x20\r\n\t]|\xc2\xa0) \'[^\']*\'
									   )*
									   #incorrect attributes
									   [^>]*+';

		if (is_array($s)) {
			if ($_callback_type === 'strip_tags') {
				$tag = strtolower($s[1]);
				if ($_allowable_tags) {
					#tag with attributes
					if (array_key_exists($tag, $_allowable_tags))
						return $s[0];

					#tag without attributes
					if (array_key_exists('<' . $tag . '>', $_allowable_tags)) {
						if (substr($s[0], 0, 2) === '</')
							return '</' . $tag . '>';
						if (substr($s[0], -2) === '/>')
							return '<' . $tag . ' />';
						return '<' . $tag . '>';
					}
				}
				if ($tag === 'br')
					return "\r\n";
				if ($_para_tags && array_key_exists($tag, $_para_tags))
					return "\r\n\r\n";
				return '';
			}
			trigger_error('Unknown callback type "' . $_callback_type . '"!', E_USER_ERROR);
		}

		if (($pos = strpos($s, '<')) === false || strpos($s, '>', $pos) === false) {
			return $s;
		}
		$length = strlen($s);
		$re_tags = '~  <[/!]?+
					   (
						   [a-zA-Z][a-zA-Z\d]*+
						   (?>:[a-zA-Z][a-zA-Z\d]*+)?
					   ) #1
					   ' . $re_attrs_fast_safe . '
					   >
					~sxSX';

		$patterns = array(
			'/<([\?\%]) .*? \\1>/sxSX',
			'/<\!\[CDATA\[ .*? \]\]>/sxSX',
			'/<\!--.*?-->/sSX',
			'/ <\! (?:--)?+
				   \[
				   (?> [^\]"\']+ | "[^"]*" | \'[^\']*\' )*
				   \]
				   (?:--)?+
			   >
			 /sxSX',
		);
		if ($pair_tags) {
			#парные таги вместе с содержимым:
			foreach ($pair_tags as $k => $v)
				$pair_tags[$k] = preg_quote($v, '/');
			$patterns[] = '/ <((?i:' . implode('|', $pair_tags) . '))' . $re_attrs_fast_safe . '(?<!\/)>
							 .*?
							 <\/(?i:\\1)' . $re_attrs_fast_safe . '>
						   /sxSX';
		}
		#d($patterns);

		$i = 0; #защита от зацикливания
		$max = 99;
		while ($i < $max) {
			$s2 = preg_replace($patterns, '', $s);
			if (preg_last_error() !== PREG_NO_ERROR) {
				$i = 999;
				break;
			}

			if ($i == 0) {
				$is_html = ($s2 != $s || preg_match($re_tags, $s2));
				if (preg_last_error() !== PREG_NO_ERROR) {
					$i = 999;
					break;
				}
				if ($is_html) {
					if ($is_format_spaces) {
						$s2 = preg_replace('/  [\x09\x0a\x0c\x0d]++
											 | <((?i:pre|textarea))' . $re_attrs_fast_safe . '(?<!\/)>
											   .+?
											   <\/(?i:\\1)' . $re_attrs_fast_safe . '>
											   \K
											/sxSX', ' ', $s2);
						if (preg_last_error() !== PREG_NO_ERROR) {
							$i = 999;
							break;
						}
					}

					#массив тагов, которые не будут вырезаны
					if ($allowable_tags)
						$_allowable_tags = array_flip($allowable_tags);

					#парные таги, которые будут восприниматься как параграфы
					if ($para_tags)
						$_para_tags = array_flip($para_tags);
				}
			}#if
			#tags processing
			if ($is_html) {
				$_callback_type = 'strip_tags';
				$s2 = preg_replace_callback($re_tags, array('Text', 'strip_tags_smart'), $s2);
				$_callback_type = false;
				if (preg_last_error() !== PREG_NO_ERROR) {
					$i = 999;
					break;
				}
			}

			if ($s === $s2)
				break;
			$s = $s2;
			$i++;
		}#while
		if ($i >= $max)
			$s = strip_tags($s);#too many cycles for replace...

		if ($is_format_spaces && strlen($s) !== $length) {
			#remove a duplicate spaces
			$s = preg_replace('/\x20\x20++/sSX', ' ', trim($s));
			#remove a spaces before and after new lines
			$s = str_replace(array("\r\n\x20", "\x20\r\n"), "\r\n", $s);
			#replace 3 and more new lines to 2 new lines
			$s = preg_replace('/[\r\n]{3,}+/sSX', "\r\n\r\n", $s);
		}
		return $s;
	}

	public static function msword_clean($text) {
		$text = str_replace("&nbsp;", "", $text);
		$text = str_replace("</html>", "", $text);
		$text = preg_replace("/FONT-SIZE: [0-9]+pt;/miu", "", $text);
		return preg_replace("/([ \f\r\t\n\'\"])on[a-z]+=[^>]+/iu", "\\1", $text);
	}

	public static function semantic_replacer($text) {
		$text = preg_replace("!<b>(.*?)</b>!si", "<strong>\\1</strong>", $text);
		$text = preg_replace("!<i>(.*?)</i>!si", "<em>\\1</em>", $text);
		$text = preg_replace("!<u>(.*?)</u>!si", "<strike>\\1</strike>", $text);
		return str_replace("<br>", "<br />", $text);
	}

	public static function simple_clean($text) {
		$text = html_entity_decode($text, ENT_QUOTES, 'utf-8');
		return self::cleanText($text);
	}

	public static function cleanText(&$text) {
		$text = preg_replace("'<script[^>]*>.*?</script>'si", '', $text);
		$text = preg_replace('/<!--.+?-->/', '', $text);
		$text = preg_replace('/{.+?}/', '', $text);
		$text = preg_replace('/&nbsp;/', ' ', $text);
		$text = preg_replace('/&amp;/', ' ', $text);
		$text = preg_replace('/&quot;/', ' ', $text);
		$text = strip_tags($text);
		return htmlspecialchars($text, null, 'UTF-8');
	}

	public static function month_name($month) {
		$all_month = array(
			1 => _JAN,
			2 => _FEB,
			3 => _MAR,
			4 => _APR,
			5 => _MAY,
			6 => _JUN,
			7 => _JUL,
			8 => _AUG,
			9 => _SEP,
			10 => _OCT,
			11 => _NOV,
			12 => _DEC
		);
		return $all_month[$month];
	}

	/**
	 *  Библиотека работы с внешними ссылками.
	 * Через функцию надо пропустить обрабатываемые текст, и все ссылки в нём заменятся на внутренние с редиректом на оригинальные.
	 * Базирутеся на примерах описанных в http://www.ewgenij.net/php-outlinks.html
	 * Функция заменятет внешние ссылки в тексте на "внутренние"
	 * Автор: Гринкевич Евгений Вадимович
	 * http://www.ewgenij.net/
	 */
	function outlink_parse($text) {

		$host = str_replace(array('http://', 'www.'), '', JPATH_SITE);
		$host = str_replace('.', '\.', $host);

		return preg_replace('/href="?(http:\/\/(?!(www\.|)' . $host . ')([^">\s]*))/ie', "'href=\"/out.php?url=' . urlencode('\$1') . '\"'", $text);
	}

}
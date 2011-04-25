<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * joosText - Библиотека работы с текстом
 * Системная библиотека
 *
 * @version 1.0
 * @package Joostina.Libraries
 * @subpackage Libraries
 * @category Libraries
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosText {

	/**
	 * Символы русского алфавита
	 * @var array
	 */
	public static $abc_ru = array(
		'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я'
	);
	/**
	 * Символы английского алфавита
	 * @var array
	 */
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
		}
		;
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
	 * Ограничение длины текста по числу слов
	 * @param string $str исходная строка
	 * @param int $limit число слов от начала строки, которое необходимо оставить
	 * @param string $end_char строка которую необходимо добавить в конец обрезанного текста
	 * @return string обработанная строка
	 */
	public static function word_limiter($str, $limit = 100, $end_char = '&#8230;') {
		if (joosString::trim($str) == '') {
			return $str;
		}

		preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/u', $str, $matches);

		$end_char = (joosString::strlen($str) == joosString::strlen($matches[0])) ? '' : $end_char;

		return joosString::rtrim($matches[0]) . $end_char;
	}

	/**
	 * Ограничение текста по числу символов
	 * @param string $str исходная строка
	 * @param int $limit число символов от начала строки, которо енеобходимо оставить
	 * @param string $end_char трока которую необходимо добавить в конец обрезанного текста
	 * @param int $max_word_lench максимальное число символов одного слова
	 * @return string обработанная строка
	 */
	public static function character_limiter($str, $limit = 500, $end_char = '&#8230;', $max_word_lench = 500) {
		if (joosString::strlen($str) < $limit) {
			return $str;
		}

		$str = preg_replace("/\s+/u", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

		if (joosString::strlen($str) <= $limit) {
			return $str;
		}

		$out = "";
		foreach (explode(' ', joosString::trim($str)) as $val) {
			if (joosString::strlen($val) > $max_word_lench) {
				$val = joosString::substr($val, 0, $max_word_lench) . $end_char;
			}
			$out .= $val . ' ';

			if (joosString::strlen($out) >= $limit) {
				$out = joosString::trim($out);
				return (joosString::strlen($out) == joosString::strlen($str)) ? $out : $out . $end_char;
			}
		}
		return joosString::substr($str, 0, $limit) . $end_char;
	}

	/**
	 * Цензор текста, заменяет в тексте указанные слова
	 * @param string $str исходная строка
	 * @param array $censored массив слов для замены
	 * @param string $replacement текст, который будет выводиться в качестве замены
	 * @return string обработанный текст
	 */
	public static function text_censor($str, array $censored, $replacement = '') {

		$delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

		foreach ($censored as $badword) {
			if ($replacement != '') {
				$str = preg_replace("/({$delim})(" . str_replace('\*', '\w*?', preg_quote($badword, '/')) . ")({$delim})/iu", "\\1{$replacement}\\3", $str);
			} else {
				$str = preg_replace("/({$delim})(" . str_replace('\*', '\w*?', preg_quote($badword, '/')) . ")({$delim})/ieu", "'\\1'.str_repeat('#', strlen('\\2')).'\\3'", $str);
			}
		}

		return joosString::trim($str);
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
	 * @param   array   $allowable_tags     Массив тагов, которые не будут вырезаны
	 *                                       Пример: 'b' -- таг останется с атрибутами, '<b>' -- таг останется без атрибутов
	 * @param   bool    $is_format_spaces   Форматировать пробелы и переносы строк?
	 *                                       Вид текста на выходе (plain) максимально приближеется виду текста в браузере на входе.
	 *                                       Другими словами, грамотно преобразует text/html в text/plain.
	 *                                       Текст форматируется только в том случае, если были вырезаны какие-либо таги.
	 * @param   array   $pair_tags   массив имён парных тагов, которые будут удалены вместе с содержимым
	 *                                см. значения по умолчанию
	 * @param   array   $para_tags   массив имён парных тагов, которые будут восприниматься как параграфы (если $is_format_spaces = true)
	 *                                см. значения по умолчанию
	 * @return  string
	 *
	 * @license  http://creativecommons.org/licenses/by-sa/3.0/
	 * @author   Nasibullin Rinat, http://orangetie.ru/
	 * @charset  ANSI
	 * @version  4.0.14
	 */
	public static function strip_tags_smart(
	/* string */
	$s, array $allowable_tags = null,
	/* boolean */ $is_format_spaces = true, array $pair_tags = array('script', 'style', 'map', 'iframe', 'frameset', 'object', 'applet', 'comment', 'button', 'textarea', 'select'), array $para_tags = array('p', 'td', 'th', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'form', 'title', 'pre')
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
			}
			#if
			#tags processing
			if ($is_html) {
				$_callback_type = 'strip_tags';
				$s2 = preg_replace_callback($re_tags, array('joosText', 'strip_tags_smart'), $s2);
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
		}
		#while
		if ($i >= $max)
			$s = strip_tags($s);
		#too many cycles for replace...

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

	/**
	 * Базовая очистка текста от тэгов создаваемых редактором MS Word
	 * @param string $text исходная строка
	 * @return string очищенная от тэгов строка
	 */
	public static function text_msword_clean($text) {
		$text = str_replace("&nbsp;", "", $text);
		$text = str_replace("</html>", "", $text);
		$text = preg_replace("/FONT-SIZE: [0-9]+pt;/miu", "", $text);
		return preg_replace("/([ \f\r\t\n\'\"])on[a-z]+=[^>]+/iu", "\\1", $text);
	}

	/**
	 * Семантическая замена тэгов на более правильные аналоги
	 * @param string $text исходная строка
	 * @return string строка с исправленными тэгами
	 */
	public static function semantic_replacer($text) {
		$text = preg_replace("!<b>(.*?)</b>!si", "<strong>\\1</strong>", $text);
		$text = preg_replace("!<i>(.*?)</i>!si", "<em>\\1</em>", $text);
		$text = preg_replace("!<u>(.*?)</u>!si", "<strike>\\1</strike>", $text);
		return str_replace("<br>", "<br />", $text);
	}

	/**
	 * Базовая очистка текста от тэгов
	 * @param string $text исходная строка текста для очистки
	 * @return string очищенная строка
	 */
	public static function simple_clean($text) {
		$text = html_entity_decode($text, ENT_QUOTES, 'utf-8');
		return self::text_clean($text);
	}

	/**
	 * Очистка текста от HTML тэгов
	 * @param string $text исходная строка для очистки
	 * @return type очищенная от тэгов строка
	 */
	public static function text_clean($text) {
		$text = preg_replace("'<script[^>]*>.*?</script>'si", '', $text);
		$text = preg_replace('/<!--.+?-->/', '', $text);
		$text = preg_replace('/{.+?}/', '', $text);
		$text = preg_replace('/&nbsp;/', ' ', $text);
		$text = preg_replace('/&amp;/', ' ', $text);
		$text = preg_replace('/&quot;/', ' ', $text);
		$text = strip_tags($text);
		return htmlspecialchars($text, null, 'UTF-8');
	}

	/**
	 * Функция работы с внешними ссылками.
	 * Через функцию надо пропустить обрабатываемые текст, и все ссылки в нём заменятся на внутренние с редиректом на оригинальные.
	 * Базирутеся на примерах описанных в http://www.ewgenij.net/php-outlinks.html
	 * Функция заменятет внешние ссылки в тексте на "внутренние"
	 * Автор: Гринкевич Евгений Вадимович
	 * http://www.ewgenij.net/
	 * @param string $text исходный текст для обработки
	 * @return string текст,  в котором все внешние ссылки заменены на редирект через внутренние
	 */
	public static function outlink_parse($text) {

		$host = str_replace(array('http://', 'www.'), '', JPATH_SITE);
		$host = str_replace('.', '\.', $host);

		// TODO сюда можно добавить base64 кодирование внешней ссылки, а out.php - дэкодирование, тогда пользователь не будет знать куда попадёт и в тексте не будет фигурировать сама ссылка
		return preg_replace('/href="?(http:\/\/(?!(www\.|)' . $host . ')([^">\s]*))/ie', "'href=\"" . JPATH_SITE . "/out.php?url=' . urlencode('\$1') . '\"'", $text);
	}

	/**
	 * Транслитерация для русского текста
	 *  на основе http://htmlweb.ru/php/example/translit.php
	 * @param string $string исходная строка
	 * @return string строка, обработанная по правилам транслитерации
	 */
	public static function russian_transliterate($string) {
		$converter = array(
			'а' => 'a', 'б' => 'b', 'в' => 'v',
			'г' => 'g', 'д' => 'd', 'е' => 'e',
			'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
			'и' => 'i', 'й' => 'y', 'к' => 'k',
			'л' => 'l', 'м' => 'm', 'н' => 'n',
			'о' => 'o', 'п' => 'p', 'р' => 'r',
			'с' => 's', 'т' => 't', 'у' => 'u',
			'ф' => 'f', 'х' => 'h', 'ц' => 'c',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
			'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
			'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
			'А' => 'A', 'Б' => 'B', 'В' => 'V',
			'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
			'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
			'И' => 'I', 'Й' => 'Y', 'К' => 'K',
			'Л' => 'L', 'М' => 'M', 'Н' => 'N',
			'О' => 'O', 'П' => 'P', 'Р' => 'R',
			'С' => 'S', 'Т' => 'T', 'У' => 'U',
			'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
			'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
			'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
		);
		return strtr($string, $converter);
	}

	// на основе http://htmlweb.ru/php/example/translit.php
	/**
	 * Преобразование строки в URL-безопасный вариант
	 * @param string $str исходная строка для обработки
	 * @return string обработанная и готовая для формирования ссылки строка
	 */
	public static function str_to_url($str) {
		// убираем непроизносимые
		$str = str_ireplace(array('ь', 'ъ'), '', $str);
		// переводим в транслит
		$str = self::russian_transliterate($str);
		// в нижний регистр
		$str = joosString::strtolower($str);
		// заменям все ненужное нам на "-"
		$str = str_replace(array("'", '-'), ' ', $str);
		$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
		return trim($str, '-');
	}

	/**
	 * Обрезание длиииинных слоооооооооооооооооов через мягкие переносы
	 * @param string $test строка для обрезки
	 * @param int $max_length максимальная длина слова
	 * @return string обрезанная строка
	 */
	public static function text_wrap($text, $max_length = 30) {
		$counter = 0;
		$newText = array();
		$array = array();

		$textLength = joosString::strlen($text);

		for ($i = 0; $i <= $textLength; $i++) {
			$array[] = joosString::substr($text, $i, 1);
		}

		$textLength = count($array);

		for ($x = 0; $x < $textLength; $x++) {
			if (preg_match("/[[:space:]]/u", $array[$x])) {
				$counter = 0;
			} else {
				$counter++;
			}

			$newText[] = $array[$x];

			if ($counter >= $max_length) {
				$newText[] = '<wbr style="display: inline-block"/>&shy;';
				$counter = 0;
			}
		}

		return implode('', $newText);
	}

	/**
	 * Преобразование текстовой строки к каноничному виду
	 * @param string $text исходная строка
	 * @return type
	 */
	public static function text_canonikal($text) {
		// приводим к единому нижнему регистру
		$text = joosString:: strtolower($text);

		// убираем спецсимволы
		$to_del = array('~', '@', '#', '$', '%', '^', '&amp;', '*', '(', ')', '-', '_', '+', '=', '|', '?', ',', '.', '/', ';', ':', '"', "'", '№', ' ', '&nbsp;');
		$text = str_replace($to_del, '', $text);

		// приводим одинаковое начертание к единому тексту
		$a = array('о', 'o', 'l', 'L', '|', '!', 'i', 'х', 's', 'а', 'р', 'с', 'в', 'к', 'е', 'й', 'ё', 'ш', 'з', 'у', 'т', 'д', 'd', 'ф', 'в', 'м', 'н', 'и', 'э', 'ь', 'ъ', 'ю');
		$b = array('0', '0', '1', '1', '1', '1', '1', 'x', '$', '0', 'p', '$', 'b', 'k', 'e', 'и', 'е', 'щ', '$', 'y', 't', 't', 't', 'b', 'b', 'm', 'h', 'e', 'e', '', '', 'u');
		$text = str_replace($a, $b, $text);

		// убираем дуУубли символов
		$return = $o = '';
		$_l = joosString::strlen($text);
		for ($i = 0; $i < $_l; $i++) {
			$c = joosString::substr($text, $i, 1);
			if ($c != $o) {
				$return .= $c;
				$o = $c;
			}
		}
		return $return;
	}

	public static function amp_replace($text) {
		$text = str_replace('&&', '*--*', $text);
		$text = str_replace('&#', '*-*', $text);
		$text = str_replace('&amp;', '&', $text);
		$text = preg_replace('|&(?![\w]+;)|', '&amp;', $text);
		$text = str_replace('*-*', '&#', $text);
		$text = str_replace('*--*', '&&', $text);
		return $text;
	}

	function mosSmartSubstr($text, $length = 200, $searchword = '') {

		$wordpos = joosString::strpos(joosString::strtolower($text), joosString::strtolower($searchword));
		$halfside = intval($wordpos - $length / 2 - joosString::strlen($searchword));
		if ($wordpos && $halfside > 0) {
			return '...' . joosString::substr($text, $halfside, $length) . '...';
		} else {
			return joosString::substr($text, 0, $length);
		}
	}

	/**
	 * Кодировкищик, позволяющий хранить 8 492 487 570 записей всего в 6 символах.
	 * @param type $string
	 * @return type
	 */
	function id_decode($string) {
		$chars = '23456789abcdeghkmnpqsuvxyzABCDEGHKLMNPQSUVXYZ'; // Используем непохожие друг на друга символы
		$length = 45; //strlen($chars); // если изменяем набор символов, то число нужно изменить
		$size = strlen($string) - 1;
		$array = str_split($string);
		$id = strpos($chars, array_pop($array));
		foreach ($array as $i => $char) {
			$id += strpos($chars, $char) * pow($length, $size - $i);
		}
		return $id;
	}

	/**
	 * @todo локализовать, описать, сделать примеры
	 */
	public static function pretty_date($from_time, $to_time = null) {
		$to_time = $to_time ? $to_time : $_SERVER['REQUEST_TIME'];

		$distance_in_minutes = floor(abs($to_time - $from_time) / 60);

		if ($distance_in_minutes <= 1)
			return 'less then a minute';
		else if ($distance_in_minutes < 60)
			return $distance_in_minutes . ' minutes ago';
		else if ($distance_in_minutes < 90)
			return '1 hour ago';
		else if ($distance_in_minutes < 1440)
			return round($distance_in_minutes / 60) . ' hours ago';
		else if ($distance_in_minutes < 2880)
			return 'Yesterday';
		else if ($distance_in_minutes < 10080)
			return round($distance_in_minutes / 1440) . ' days ago';
		else if ($distance_in_minutes < 43200)
			return round($distance_in_minutes / 10080) . ' weeks ago';
		else if ($distance_in_minutes < 86400)
			return '1 month ago';
		else if ($distance_in_minutes < 525960)
			return round($distance_in_minutes / 43200) . ' months ago';
		else if ($distance_in_minutes < 1051920)
			return '1 year ago';
		else
			return 'more then ' . round($distance_in_minutes / 525960) . ' years ago';
	}

}
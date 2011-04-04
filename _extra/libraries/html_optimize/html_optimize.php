<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

// на основе Web Optimizer by Nikolay Matsievsky (http://webo.in)

class html_optimize {

	public static function optimize($source) {
		preg_match_all("!(<script.*?</script>|<textarea.*?</textarea>|<pre.*?</pre>)!is", $source, $match);
		$_script_blocks = $match[0];
		$source = preg_replace("!(<script.*?</script>|<textarea.*?</textarea>|<pre.*?</pre>)!is", '@@@COMPRESSOR:TRIM:SCRIPT@@@', $source);
		$source = trim(preg_replace('/((?<!\?>)\n)[\s]+/m', '\1', $source));
		$source = preg_replace("/[\s\t\r\n]*(<\/?)(!--|!DOCTYPE|address|area|audioscope|base|bgsound|blockquote|body|br|caption|center|col|colgroup|comment|dd|div|dl|dt|embed|fieldset|form|frame|frameset|h[123456]|head|hr|html|iframe|keygen|layer|legend|li|link|map|marquee|menu|meta|noembed|noframes|noscript|object|ol|optgroup|option|p|param|samp|script|select|sidebar|style|table|tbody|td|tfoot|th|title|tr|ul|var)([^>]*)>[\s\t\r\n]+/i", "$1$2$3>", $source);
		$source = preg_replace("/(<\/?)(a|abbr|acronym|b|basefont|bdo|big|blackface|blink|button|cite|code|del|dfn|dir|em|font|i|img|input|ins|isindex|kbd|label|q|s|small|span|strike|strong|sub|sup|u)([^>]*)>[\s\t\r\n]+/i", "$1$2$3> ", $source);
		$source = preg_replace("/\s\/>/", "/>", $source);
		self::trimwhitespace_replace("@@@COMPRESSOR:TRIM:SCRIPT@@@", $_script_blocks, $source);
		return preg_replace("/script>\s+/", "script>", $source);
	}

	private static function trimwhitespace_replace($search_str, $replace, &$subject) {
		$_len = strlen($search_str);
		$_pos = 0;
		for ($_i = 0, $_count = count($replace); $_i < $_count; $_i++) {
			if (($_pos = strpos($subject, $search_str, $_pos)) !== false) {
				$subject = substr_replace($subject, $replace[$_i], $_pos, $_len);
			} else {
				break;
			}
		}
	}

}
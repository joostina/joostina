<?php

class joosUtils {

	// на основе templatecms_2_0_3
	function compressCSS($buffer) {
		// Remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

		// Remove tabs, spaces, newlines, etc.
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);

		// Preserve empty comment after '>' http://www.webdevout.net/css-hacks#in_css-selectors
		$buffer = preg_replace('@>/\\*\\s*\\*/@', '>/*keep*/', $buffer);

		// Preserve empty comment between property and value
		// http://css-discuss.incutio.com/?page=BoxModelHack
		$buffer = preg_replace('@/\\*\\s*\\*/\\s*:@', '/*keep*/:', $buffer);
		$buffer = preg_replace('@:\\s*/\\*\\s*\\*/@', ':/*keep*/', $buffer);

		// Remove ws around { } and last semicolon in declaration block
		$buffer = preg_replace('/\\s*{\\s*/', '{', $buffer);
		$buffer = preg_replace('/;?\\s*}\\s*/', '}', $buffer);

		// Remove ws surrounding semicolons
		$buffer = preg_replace('/\\s*;\\s*/', ';', $buffer);

		// Remove ws around urls
		$buffer = preg_replace('/url\\(\\s*([^\\)]+?)\\s*\\)/x', 'url($1)', $buffer);

		// Remove ws between rules and colons
		$buffer = preg_replace('/\\s*([{;])\\s*([\\*_]?[\\w\\-]+)\\s*:\\s*(\\b|[#\'"])/x', '$1$2:$3', $buffer);

		// Minimize hex colors
		$buffer = preg_replace('/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i', '$1#$2$3$4$5', $buffer);

		// Replace any ws involving newlines with a single newline
		$buffer = preg_replace('/[ \\t]*\\n+\\s*/', "\n", $buffer);

		return $buffer;
	}

	/**
	 * This prevents null characters between ascii characters.
	 * @param string $str String
	 */
	function removeInvisibleCharacters($str) {
		// Thanks to ci for this tip :)
		$non_displayables = array('/%0[0-8bcef]/', '/%1[0-9a-f]/', '/[\x00-\x08]/', '/\x0b/', '/\x0c/', '/[\x0e-\x1f]/');

		do {
			$cleaned = $str;
			$str = preg_replace($non_displayables, '', $str);
		} while ($cleaned != $str);

		return $str;
	}

	/**
	 * Sanitize data to prevent XSS - Cross-site scripting
	 * @param string $str String
	 */
	function xssClean($str) {

		// Remove invisible characters
		$str = removeInvisibleCharacters($str);

		// Convert html to plain text
		$str = toText($str);

		return $str;
	}

}
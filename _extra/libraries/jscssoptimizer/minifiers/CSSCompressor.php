<?php
/**
 * This is a port of the CSS Compressor contained in YUI Compressor
 * The original license is below
 *
 * Port by Dave T. Johnson <dave@dtjohnson.net>
 *
 * Usage: $minified = CSSCompressor::minify($source);
 *
 ******************************************************************
 *
 * YUI Compressor
 * Author: Julien Lecomte <jlecomte@yahoo-inc.com>
 * Copyright (c) 2007, Yahoo! Inc. All rights reserved.
 * Code licensed under the BSD License:
 *     http://developer.yahoo.net/yui/license.txt
 *
 * This code is a port of Isaac Schlueter's cssmin utility.
 */

class CSSCompressor {
	public static function minify($source, $linebreakpos=0) {
		// Remove all comment blocks...
		$startIndex = 0;
		$iemac = false;
		$preserve = false;
		while ($startIndex < strlen($source)) {
			$startIndex = strpos($source, '/*', $startIndex + 2);
			if ($startIndex === false) break;
			$preserve = strlen($source) > $startIndex + 2 && $source[$startIndex + 2] == '!';
			$endIndex = strpos($source, '*/', $startIndex + 2);

			if ($endIndex === false) {
				if (!$preserve) {
					$source = substr($source, 0, $startIndex);
				}
			} elseif ($endIndex >= $startIndex + 2) {
				if ($source[$endIndex - 1] == '\\') {
					// Looks like a comment to hide rules from IE Mac.
					// Leave this comment, and the following one, alone...
					$startIndex = $endIndex + 2;
					$iemac = true;
				} elseif ($iemac) {
					$startIndex = $endIndex + 2;
					$iemac = false;
				} elseif (!$preserve) {
					$source = substr($source, 0, $startIndex).substr($source, $endIndex+2);
				} else {
					//Strip !
					$source = substr($source, 0, $startIndex+2).substr($source, $startIndex+3);
				}
			}
		}
		
		// Normalize all whitespace strings to single spaces. Easier to work with that way.
		$source = preg_replace('/\s+/', ' ', $source);
		
		// Replace the pseudo class for the Box Model Hack
		$source = preg_replace('~"\\\\"}\\\\""~', '___PSEUDOCLASSBMH___', $source);

		// Remove the spaces before the things that should not have spaces before them.
		// But, be careful not to turn "p :link {...}" into "p:link{...}"
		// Swap out any pseudo-class colons with the token, and then swap back.
		$source = preg_replace_callback('~(^|\})(([^\{:])+:)+([^\{]*\{)~', create_function('$matches', '
			return str_replace(":", "___PSEUDOCLASSCOLON___", $matches[0]);
		'), $source);
		$source = preg_replace('~\s+([!{};:>+\(\)\],])~', '$1', $source);
		$source = str_replace('___PSEUDOCLASSCOLON___', ':', $source);

		// Remove the spaces after the things that should not have spaces after them.
		$source = preg_replace('~([!{}:;>+\(\[,])\s+~', '$1', $source);

		// Add the semicolon where it's missing.
		$source = preg_replace('~([^;\}])}~', '$1;}', $source);

		// Replace 0(px,em,%) with 0.
		$source = preg_replace('~([\s:])(0)(px|em|%|in|cm|mm|pc|pt|ex)~', '$1$2', $source);

		// Replace 0 0 0 0; with 0.
		$source = preg_replace('~:0(\s0){1,3};~', ':0;', $source);
		
		// Replace background-position:0; with background-position:0 0;
		$source = str_replace('background-position:0;', 'background-position:0 0;', $source);

		// Replace 0.6 to .6, but only when preceded by : or a white-space
		$source = preg_replace('~(:|\s)0+\.(\d+)~', '$1.$2', $source);

		// Shorten colors from rgb(51,102,153) to #336699
		// This makes it more likely that it'll get further compressed in the next step.
		$source = preg_replace_callback('~rgb\s*\(\s*([0-9,\s]+)\s*\)~', create_function('$matches', '
				$colors = explode(",", $matches[1]);
				$hexcolor = "#";
				foreach ($colors as $color) {
					$color = (int)$color;
					if ($color < 16) $hexcolor .= "0";
					$hexcolor .= dechex($color);
				}
				return $hexcolor;
		'), $source);

		// Shorten colors from #AABBCC to #ABC. Note that we want to make sure
		// the color is not preceded by either ", " or =. Indeed, the property
		//     filter: chroma(color="#FFFFFF");
		// would become
		//     filter: chroma(color="#FFF");
		// which makes the filter break in IE.
		$source = preg_replace('~([^"\'=\s])(\s*)#([0-9a-fA-F])\3([0-9a-fA-F])\4([0-9a-fA-F])\5~', '$1$2#$3$4$5', $source);

		// Remove empty rules.
		$source = preg_replace('~[^\}]+\{;\}~', '', $source);

		if ($linebreakpos) {
			// Some source control tools don't like it when files containing lines longer
			// than, say 8000 characters, are checked in. The linebreak option is used in
			// that case to split long lines after a specific column.
			$i = 0;
			$linestartpos = 0;
			$temp = '';
			while ($i < strlen($source)) {
				$c = $source[$i++];
				if ($c == '}' && $i - $linestartpos > $linebreakpos) {
					$temp .= $c."\n";
					$linestartpos = $i;
				} else {
					$temp .= $c;
				}
			}
			$source = $temp;
		}

		// Replace the pseudo class for the Box Model Hack
		$source = preg_replace('/___PSEUDOCLASSBMH___/', '"\\"}\\""', $source);

		// Replace multiple semi-colons in a row by a single one
		// See SF bug #1980989
		$source = preg_replace('/;;+/', ';', $source);

		// Trim the final string (for any leading or trailing white spaces)
		$source = trim($source);

		return $source;
	}
}

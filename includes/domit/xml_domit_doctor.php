<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*
* dom_xmlrpc_array_document wraps a PHP array with the DOM XML-RPC API
* @package dom-xmlrpc
* @copyright (C) 2004 John Heinstein. All rights reserved
* @license http://www.gnu.org/copyleft/lesser.html LGPL License
* @author John Heinstein <johnkarl@nbnet.nb.ca>
* @link http://www.engageinteractive.com/dom_xmlrpc/ DOM XML-RPC Home Page
* DOM XML-RPC is Free Software
**/

defined('_JOOS_CORE') or die();
class domit_doctor {
	public static function fixAmpersands($xmlText) {
		$xmlText = trim($xmlText);
		$startIndex = -1;
		$processing = true;
		$illegalChar = '&';
		while($processing) {
			$startIndex = strpos($xmlText,$illegalChar,($startIndex + 1));
			if($startIndex !== false) {
				$xmlText = domit_doctor::evaluateCharacter($xmlText,$illegalChar,($startIndex +
					1));
			} else {
				$processing = false;
			}
		}
		return $xmlText;
	}

	function evaluateCharacter($xmlText,$illegalChar,$startIndex) {
		$total = strlen($xmlText);
		$searchingForCDATASection = false;
		for($i = $startIndex; $i < $total; $i++) {
			$currChar = substr($xmlText,$i,1);
			if(!$searchingForCDATASection) {
				switch($currChar) {
					case ' ':
					case "'":
					case '"':
					case "\n":
					case "\r":
					case "\t":
					case '&':
					case "]":
						$searchingForCDATASection = true;
						break;
					case ";":
						return $xmlText;
						break;
				}
			} else {
				switch($currChar) {
					case '<':
					case '>':
						return (substr_replace($xmlText,'&amp;',($startIndex - 1),1));
						break;
					case "]":
						return $xmlText;
						break;
				}
			}
		}
		return $xmlText;
	}
}
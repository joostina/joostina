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
$GLOBALS['DOMIT_PREDEFINED_ENTITIES'] = array('&' => '&amp;','<' => '&lt;','>' =>
	'&gt;','"' => '&quot;',"'" => '&apos;');
class DOMIT_Utilities {
	function DOMIT_Utilities() {
		die("DOMIT_Utilities Error: this is a static class that should never be instantiated.\n".
			"Please use the following syntax to access methods of this class:\n".
			'DOMIT_Utilities::methodName(parameters)');
	}

	function toNormalizedString(&$node,$subEntities = false,$definedEntities) {
		$node_level = 0;
		$response = '';

		if($node->nodeType == DOMIT_DOCUMENT_NODE) {
			$total = $node->childCount;
			for($i = 0; $i < $total; $i++) {
				$response .= DOMIT_Utilities::getNormalizedString($node->childNodes[$i],$node_level,
					$subEntities,$definedEntities);
			}
			return $response;
		} else {
			return ($response.DOMIT_Utilities::getNormalizedString($node,$node_level,$subEntities,
				$definedEntities));
		}
	}

	function convertEntities($text,$definedEntities) {
		global $DOMIT_PREDEFINED_ENTITIES;
		$result = strtr($text,$DOMIT_PREDEFINED_ENTITIES);
		$result = strtr($result,$definedEntities);
		return $result;
	}

	function getNormalizedString(&$node,$node_level,$subEntities = false,$definedEntities) {
		$response = '';
		switch($node->nodeType) {
			case DOMIT_ELEMENT_NODE:
				$response .= DOMIT_Utilities::getNormalizedElementString($node,$response,$node_level,
					$subEntities,$definedEntities);
				break;
			case DOMIT_TEXT_NODE:
				if($node->nextSibling == null) {
					$node_level--;
				}
				$response .= ($subEntities?DOMIT_Utilities::convertEntities($node->nodeValue,$definedEntities):
					$node->nodeValue);
				break;
			case DOMIT_CDATA_SECTION_NODE:
				if($node->nextSibling == null) {
					$node_level--;
				}
				$response .= '<![CDATA['.$node->nodeValue.']]>';
				break;
			case DOMIT_ATTRIBUTE_NODE:
				$response .= $node->toString(false,$subEntities);
				break;
			case DOMIT_DOCUMENT_FRAGMENT_NODE:
				$total = $node->childCount;
				for($i = 0; $i < $total; $i++) {
					$response .= DOMIT_Utilities::getNormalizedString($node->childNodes[$i],$node_level,
						$subEntities,$definedEntities);
				}
				break;
			case DOMIT_COMMENT_NODE:
				$response .= '<!--'.$node->nodeValue.'-->';
				if($node->nextSibling == null) {
					$node_level--;
				}
				$response .= DOMIT_Utilities::getIndentation($node_level);
				break;
			case DOMIT_PROCESSING_INSTRUCTION_NODE:
				$response .= '<'.'?'.$node->nodeName.' '.$node->nodeValue.'?'.'>';
				if($node->nextSibling == null) {
					$node_level--;
				}
				$response .= DOMIT_Utilities::getIndentation($node_level);
				break;
			case DOMIT_DOCUMENT_TYPE_NODE:
				$response .= $node->toString()."\n";
				break;
		}
		return $response;
	}

	function getNormalizedElementString(&$node,$response,$node_level,$subEntities,$definedEntities) {
		$response .= '<'.$node->nodeName;

		if(is_object($node->attributes)) {

			$response .= $node->attributes->toString(false,$subEntities);
		} else {

			foreach($node->attributes as $key => $value) {
				$response .= ' '.$key.'="';
				$response .= ($subEntities?DOMIT_Utilities::convertEntities($value,$definedEntities):
					$value);
				$response .= '"';
			}
		}
		$node_level++;

		if($node->childCount == 0) {
			if($node->ownerDocument->doExpandEmptyElementTags) {
				if(in_array($node->nodeName,$node->ownerDocument->expandEmptyElementExceptions)) {
					$response .= ' />';
				} else {
					$response .= '></'.$node->nodeName.'>';
				}
			} else {
				if(in_array($node->nodeName,$node->ownerDocument->expandEmptyElementExceptions)) {
					$response .= '></'.$node->nodeName.'>';
				} else {
					$response .= ' />';
				}
			}
		} else {
			$response .= '>';

			$myNodes = &$node->childNodes;
			$total = $node->childCount;

			if(!DOMIT_Utilities::isTextNode($node->firstChild)) {
				$response .= DOMIT_Utilities::getIndentation($node_level);
			}
			for($i = 0; $i < $total; $i++) {
				$child = &$myNodes[$i];
				$response .= DOMIT_Utilities::getNormalizedString($child,$node_level,$subEntities,
					$definedEntities);
			}
			$response .= '</'.$node->nodeName.'>';
		}
		$node_level--;
		if($node->nextSibling == null) {
			$node_level--;
			$response .= DOMIT_Utilities::getIndentation($node_level);
		} else {

			if(!DOMIT_Utilities::isTextNode($node->nextSibling)) {
				$response .= DOMIT_Utilities::getIndentation($node_level);
			}
		}
		return $response;
	}

	function isTextNode(&$node) {
		$type = $node->nodeType;
		return (($type == DOMIT_TEXT_NODE) || ($type == DOMIT_CDATA_SECTION_NODE));
	}

	function getIndentation($node_level) {
		$INDENT_LEN = '    ';
		$indentation = "\n";
		for($i = 0; $i < $node_level; $i++) {
			$indentation .= $INDENT_LEN;
		}
		return $indentation;
	}

	function removeExtension($fileName) {
		$total = strlen($fileName);
		$index = -1;
		for($i = ($total - 1); $i >= 0; $i--) {
			if($fileName{$i} == '.') {
				$index = $i;
			}
		}
		if($index == -1) {
			return $fileName;
		}
		return (substr($fileName,0,$index));
	}

	public static function validateXML($xmlText) {


		$isValid = true;
		if(is_string($xmlText)) {
			$text = trim($xmlText);
			switch($text) {
				case '':
					$isValid = false;
					break;
			}
		} else {
			$isValid = false;
		}
		return $isValid;
	}

	function printUTF8Header($contentType = 'text/html') {
		echo header('Content-type: '.$contentType.'; charset=utf-8');
	}

	function forHTML($text,$doPrint = false) {
		if($doPrint) {
			print ('<pre>'.htmlspecialchars($text).'</pre>');
		} else {
			return ('<pre>'.htmlspecialchars($text).'</pre>');
		}
		return;
	}

	function fromArray(&$node,&$myArray) {
		if($node->nodeType == DOMIT_DOCUMENT_NODE) {
			$docNode = &$node;
		} else {
			$docNode = &$node->ownerDocument;
		}
		foreach($myArray as $key => $value) {
			if(is_array($value)) {

				$total = count($value);
				if(($total > 0) && isset($value[0])) {
					for($i = 0; $i < $total; $i++) {
						$node->appendChild($docNode->createElement($key));
						DOMIT_Utilities::fromArray($node->lastChild,$value[$i]);
					}
				} else {

					$node->appendChild($docNode->createElement($key));
					DOMIT_Utilities::fromArray($node->lastChild,$value);
				}
			} else {
				$node->appendChild($docNode->createElement($key));
				$node->lastChild->appendChild($docNode->createTextNode($value));
			}
		}
	}
	function parseAttributes() {}
}
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

defined('_VALID_MOS') or die();
if(!defined('DOMIT_INCLUDE_PATH')) {
	define('DOMIT_INCLUDE_PATH',(dirname(__file__)."/"));
}
define('GET_ELEMENTS_BY_PATH_SEPARATOR','/');
define('GET_ELEMENTS_BY_PATH_SEARCH_ABSOLUTE',0);
define('GET_ELEMENTS_BY_PATH_SEARCH_RELATIVE',1);
define('GET_ELEMENTS_BY_PATH_SEARCH_VARIABLE',2);
class DOMIT_GetElementsByPath {
	var $callingNode;
	var $searchType;
	var $contextNode;
	var $arPathSegments = array();
	var $nodeList;
	var $targetIndex;
	var $abortSearch = false;
	function DOMIT_GetElementsByPath() {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_nodemaps.php');
		$this->nodeList = new DOMIT_NodeList();
	}

	function &parsePattern(&$node,$pattern,$nodeIndex = 0) {
		$this->callingNode = &$node;
		$pattern = trim($pattern);
		$this->determineSearchType($pattern);
		$this->setContextNode();
		$this->splitPattern($pattern);
		$this->targetIndex = $nodeIndex;
		$totalSegments = count($this->arPathSegments);
		if($totalSegments > 0) {
			if($this->searchType == GET_ELEMENTS_BY_PATH_SEARCH_VARIABLE) {
				$arContextNodes = &$this->contextNode->ownerDocument->getElementsByTagName($this->arPathSegments[0]);
				$totalContextNodes = $arContextNodes->getLength();
				for($i = 0; $i < $totalContextNodes; $i++) {
					$this->selectNamedChild($arContextNodes->item($i),1);
				}
			} else {
				if($this->searchType == GET_ELEMENTS_BY_PATH_SEARCH_ABSOLUTE) {
					if($this->contextNode->nodeName == $this->arPathSegments[0]) {
						if(count($this->arPathSegments) == 1) {
							$this->nodeList->appendNode($this->contextNode);
						} else {
							$this->selectNamedChild($this->contextNode,1);
						}
					}
				} else
					if($this->searchType == GET_ELEMENTS_BY_PATH_SEARCH_RELATIVE) {
						$this->selectNamedChild($this->contextNode,0);
					}
			}
		}
		if($nodeIndex > 0) {
			if($nodeIndex <= $this->nodeList->getLength()) {
				return $this->nodeList->item(($nodeIndex - 1));
			} else {
				$null = null;
				return $null;
			}
		}
		return $this->nodeList;
	}

	function determineSearchType($pattern) {
		$firstChar = $pattern{0};
		if($firstChar != GET_ELEMENTS_BY_PATH_SEPARATOR) {

			$this->searchType = GET_ELEMENTS_BY_PATH_SEARCH_RELATIVE;
		} else {
			$secondChar = $pattern{1};
			if($secondChar != GET_ELEMENTS_BY_PATH_SEPARATOR) {

				$this->searchType = GET_ELEMENTS_BY_PATH_SEARCH_ABSOLUTE;
			} else {

				$this->searchType = GET_ELEMENTS_BY_PATH_SEARCH_VARIABLE;
			}
		}
	}

	function setContextNode() {
		switch($this->searchType) {
			case GET_ELEMENTS_BY_PATH_SEARCH_ABSOLUTE:
				$this->contextNode = &$this->callingNode->ownerDocument->documentElement;
				break;
			case GET_ELEMENTS_BY_PATH_SEARCH_RELATIVE:
				if($this->callingNode->uid != $this->callingNode->ownerDocument->uid) {
					$this->contextNode = &$this->callingNode;
				} else {
					$this->contextNode = &$this->callingNode->ownerDocument->documentElement;
				}
				break;
			case GET_ELEMENTS_BY_PATH_SEARCH_VARIABLE:
				$this->contextNode = &$this->callingNode->ownerDocument->documentElement;
				break;
		}
	}

	function splitPattern($pattern) {
		switch($this->searchType) {
			case GET_ELEMENTS_BY_PATH_SEARCH_ABSOLUTE:
				$lastIndex = 1;
				break;
			case GET_ELEMENTS_BY_PATH_SEARCH_RELATIVE:
				$lastIndex = 0;
				break;
			case GET_ELEMENTS_BY_PATH_SEARCH_VARIABLE:
				$lastIndex = 2;
				break;
		}
		$this->arPathSegments = explode(GET_ELEMENTS_BY_PATH_SEPARATOR,substr($pattern,
			$lastIndex));
	}

	function selectNamedChild(&$node,$pIndex) {
		if(!$this->abortSearch) {
			if($pIndex < count($this->arPathSegments)) {

				$name = $this->arPathSegments[$pIndex];
				$numChildren = $node->childCount;
				for($i = 0; $i < $numChildren; $i++) {
					$currentChild = &$node->childNodes[$i];
					if($currentChild->nodeName == $name) {
						$this->selectNamedChild($currentChild,($pIndex + 1));
					}
				}
			} else {
				$this->nodeList->appendNode($node);
				if($this->targetIndex == $this->nodeList->getLength()) {
					$this->abortSearch = true;
				}
			}
		}
	}

}

class DOMIT_GetElementsByAttributePath {
	var $nodeList;
	function DOMIT_GetElementsByAttributePath() {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_nodemaps.php');
		$this->nodeList = new DOMIT_NodeList();
	}

	function &parsePattern(&$node,$pattern,$nodeIndex = 0) {
		$beginSquareBrackets = strpos($pattern,'[');
		if($beginSquareBrackets != 0) {
			$path = substr($pattern,0,$beginSquareBrackets);
			$attrPattern = substr($pattern,(strpos($pattern,'@') + 1));
			$attrPattern = substr($attrPattern,0,strpos($attrPattern,')'));
			$commaIndex = strpos($attrPattern,',');
			$key = trim(substr($attrPattern,0,$commaIndex));
			$value = trim(substr($attrPattern,($commaIndex + 1)));
			$value = substr($value,1,(strlen($value) - 2));
			$gebp = new DOMIT_GetElementsByPath();
			$myResponse = &$gebp->parsePattern($node,$path);
			$total = $myResponse->getLength();
			for($i = 0; $i < $total; $i++) {
				$currNode = &$myResponse->item($i);
				if($currNode->hasAttribute($key)) {
					if($currNode->getAttribute($key) == $value) {
						$this->nodeList->appendNode($currNode);
					}
				}
			}
		}
		if($nodeIndex == 0) {
			return $this->nodeList;
		} else {
			if($nodeIndex <= $this->nodeList->getLength()) {
				return $this->nodeList->item(($nodeIndex - 1));
			} else {
				$this->nodeList = new DOMIT_NodeList();
				return $this->nodeList;
			}
		}
	}
}
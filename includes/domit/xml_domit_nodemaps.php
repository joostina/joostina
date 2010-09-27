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
if(!defined('DOMIT_INCLUDE_PATH')) {
	define('DOMIT_INCLUDE_PATH',(dirname(__file__)."/"));
}
class DOMIT_NodeList {
	var $arNodeList = array();
	function &item($index) {
		if($index < $this->getLength()) {
			return $this->arNodeList[$index];
		}
		return null;
	}

	function getLength() {
		return count($this->arNodeList);
	}

	function &appendNode(&$node) {
		$this->arNodeList[] = &$node;
		return $node;
	}

	function &removeNode(&$node) {
		$total = $this->getLength();
		$returnNode = null;
		$found = false;
		for($i = 0; $i < $total; $i++) {
			if(!$found) {
				if($node->uid == $this->arNodeList[$i]->uid) {
					$found = true;
					$returnNode = &$node;
				}
			}
			if($found) {
				if($i == ($total - 1)) {
					unset($this->arNodeList[$i]);
				} else {
					$this->arNodeList[$i] = &$this->arNodeList[($i + 1)];
				}
			}
		}
		return $returnNode;
	}

	function forHTML($str,$doPrint = false) {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_utilities.php');
		return DOMIT_Utilities::forHTML($str,$doPrint);
	}

	function toArray() {
		return $this->arNodeList;
	}

	function &createClone($deep = false) {
		$className = get_class($this);
		$clone = new $className();
		foreach($this->arNodeList as $key => $value) {
			$currNode = &$this->arNodeList[$key];
			$clone->arNodeList[$key] = &$currNode->cloneNode($deep);
		}
		return $clone;
	}

	function toString($htmlSafe = false,$subEntities = false) {
		$result = '';
		foreach($this->arNodeList as $key => $value) {
			$currNode = &$this->arNodeList[$key];
			$result .= $currNode->toString(false,$subEntities);
		}
		if($htmlSafe)
			$result = $this->forHTML($result);
		return $result;
	}

	function toNormalizedString($htmlSafe = false,$subEntities = false) {
		$result = '';
		foreach($this->arNodeList as $key => $value) {
			$currNode = &$this->arNodeList[$key];
			$result .= $currNode->toNormalizedString(false,$subEntities);
		}
		if($htmlSafe)
			$result = $this->forHTML($result);
		return $result;
	}

}

class DOMIT_NamedNodeMap {
	var $arNodeMap = array();
	var $indexedNodeMap = array();
	var $isDirty = true;
	function &getNamedItem($name) {
		if(isset($this->arNodeMap[$name])) {
			return $this->arNodeMap[$name];
		}
		$null = null;
		return $null;
	}

	function reindexNodeMap() {
		$this->indexedNodeMap = array();
		foreach($this->arNodeMap as $key => $value) {
			$this->indexedNodeMap[] = $key;
		}
		$this->isDirty = false;
	}

	function &setNamedItem(&$arg) {
		$returnNode = null;
		if(isset($this->arNodeMap[$arg->nodeName])) {
			$returnNode = &$this->arNodeMap[$arg->nodeName];
		} else {
			$this->isDirty = true;
		}
		$this->arNodeMap[$arg->nodeName] = &$arg;
		return $returnNode;
	}

	function &removeNamedItem($name) {
		$returnNode = null;
		if(isset($this->arNodeMap[$name])) {
			$returnNode = &$this->arNodeMap[$name];
			unset($this->arNodeMap[$name]);
			$this->isDirty = true;
		}
		return $returnNode;
	}

	function &getNamedItemNS($namespaceURI,$localName) {
		$key = $this->getKeyNS($namespaceURI,$localName);

		if(isset($this->arNodeMap[$key])) {
			return $this->arNodeMap[$key];
		}


		if(isset($this->arNodeMap[$localName])) {

			$firstAttr = &$this->item(1);
			$ownerElem = &$firstAttr->ownerElement;
			if($namespaceURI == $ownerElem->namespaceURI) {
				return $this->arNodeMap[$localName];
			}
		}
		$null = null;
		return $null;
	}

	function &setNamedItemNS(&$arg) {
		$returnNode = null;
		$key = $this->getKeyNS($arg->namespaceURI,$arg->localName);
		if(isset($this->arNodeMap[$key])) {
			$returnNode = &$this->arNodeMap[$key];
		} else {
			$this->isDirty = true;
		}
		$this->arNodeMap[$key] = &$arg;
		return $returnNode;
	}

	function &removeNamedItemNS($namespaceURI,$localName) {
		$returnNode = null;
		$key = $this->getKeyNS($namespaceURI,$localName);
		if(isset($this->arNodeMap[$key])) {
			$returnNode = &$this->arNodeMap[$key];
			unset($this->arNodeMap[$key]);
			$this->isDirty = true;
		}
		return $returnNode;
	}

	function getKeyNS($namespaceURI,$localName) {
		if($namespaceURI != '') {
			return $namespaceURI.":".$localName;
		}
		return $localName;
	}

	function &item($index) {
		if($this->isDirty)
			$this->reindexNodeMap();
		return $this->arNodeMap[$this->indexedNodeMap[$index]];
	}

	function getLength() {
		return count($this->arNodeMap);
	}

	function forHTML($str,$doPrint = false) {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_utilities.php');
		return DOMIT_Utilities::forHTML($str,$doPrint);
	}

	function toArray() {
		return $this->arNodeMap;
	}

	function &createClone($deep = false) {
		$className = get_class($this);
		$clone = new $className();
		foreach($this->arNodeMap as $key => $value) {
			$currNode = &$this->arNodeMap[$key];
			$clone->arNodeMap[$key] = &$currNode->cloneNode($deep);
		}
		return $clone;
	}

	function toString($htmlSafe = false,$subEntities = false) {
		$result = '';
		foreach($this->arNodeMap as $key => $value) {
			$currNode = &$this->arNodeMap[$key];
			$result .= $currNode->toString(false,$subEntities);
		}
		if($htmlSafe)
			$result = $this->forHTML($result);
		return $result;
	}

	function toNormalizedString($htmlSafe = false,$subEntities = false) {
		$result = '';
		foreach($this->arNodeMap as $key => $value) {
			$currNode = &$this->arNodeMap[$key];
			$result .= $currNode->toNormalizedString(false,$subEntities);
		}
		if($htmlSafe)
			$result = $this->forHTML($result);
		return $result;
	}

}

class DOMIT_NamedNodeMap_Attr extends DOMIT_NamedNodeMap {
	function toArray() {
		$arReturn = array();
		foreach($this->arNodeMap as $key => $value) {
			$arReturn[$key] = $this->arNodeMap[$key]->getValue();
		}
		return $arReturn;
	}

	function toString($htmlSafe = false,$subEntities = false) {
		$result = '';
		foreach($this->arNodeMap as $key => $value) {
			$currNode = &$this->arNodeMap[$key];
			$result .= $currNode->toString(false,$subEntities);
		}
		if($htmlSafe)
			$result = $this->forHTML($result);
		return $result;
	}
}
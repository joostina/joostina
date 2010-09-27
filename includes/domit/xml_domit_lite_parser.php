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
define('DOMIT_LITE_VERSION','1.01');
$GLOBALS['DOMIT_defined_entities_flip'] = array();
require_once (DOMIT_INCLUDE_PATH.'xml_domit_shared.php');
class DOMIT_Lite_Node {
	var $nodeName = null;
	var $nodeValue = null;
	var $nodeType = null;
	var $parentNode = null;
	var $childNodes = null;
	var $firstChild = null;
	var $lastChild = null;
	var $previousSibling = null;
	var $nextSibling = null;
	var $attributes = null;
	var $ownerDocument = null;
	var $uid;
	var $childCount = 0;
	function DOMIT_Lite_Node() {
		DOMIT_DOMException::raiseException(DOMIT_ABSTRACT_CLASS_INSTANTIATION_ERR,'Cannot instantiate abstract class DOMIT_Lite_Node');
	}

	function _constructor() {
		global $uidFactory;
		$this->uid = $uidFactory->generateUID();
	}

	function appendChild($node) {
		DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Method appendChild cannot be called by class '.get_class($this)));
	}

	function insertBefore($newChild,$refChild) {
		DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Method insertBefore cannot be called by class '.get_class($this)));
	}

	function replaceChild($newChild,$oldChild) {
		DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Method replaceChild cannot be called by class '.get_class($this)));
	}

	function removeChild($oldChild) {
		DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Method removeChild cannot be called by class '.get_class($this)));
	}

	function getChildNodeIndex($arr,$child) {
		$index = -1;
		$total = count($arr);
		for($i = 0; $i < $total; $i++) {
			if($child->uid == $arr[$i]->uid) {
				$index = $i;
				break;
			}
		}
		return $index;
	}

	function hasChildNodes() {
		return ($this->childCount > 0);
	}

	function cloneNode($deep = false) {
		DOMIT_DOMException::raiseException(DOMIT_ABSTRACT_METHOD_INVOCATION_ERR,'Cannot invoke abstract method DOMIT_Lite_Node->cloneNode($deep). Must provide an overridden method in your subclass.');
	}

	function getNamedElements($nodeList,$tagName) {
		return;
	}

	function setOwnerDocument($rootNode) {
		if($rootNode->ownerDocument == null) {
			unset($this->ownerDocument);
			$this->ownerDocument = null;
		} else {
			$this->ownerDocument = $rootNode->ownerDocument;
		}
		$total = $this->childCount;
		for($i = 0; $i < $total; $i++) {
			$this->childNodes[$i]->setOwnerDocument($rootNode);
		}
	}

	function nvl($value,$default) {
		if(is_null($value))
			return $default;
		return $value;
	}

	function getElementsByPath($pattern,$nodeIndex = 0) {
		DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Method getElementsByPath cannot be called by class '.get_class($this)));
	}

	function getText() {
		return $this->nodeValue;
	}

	function forHTML($str,$doPrint = false) {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_utilities.php');
		return DOMIT_Utilities::forHTML($str,$doPrint);
	}

	function toArray() {
		DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Method toArray cannot be called by class '.get_class($this)));
	}

	function onLoad() {}

	function clearReferences() {
		if($this->previousSibling != null) {
			unset($this->previousSibling);
			$this->previousSibling = null;
		}
		if($this->nextSibling != null) {
			unset($this->nextSibling);
			$this->nextSibling = null;
		}
		if($this->parentNode != null) {
			unset($this->parentNode);
			$this->parentNode = null;
		}
	}

	function toNormalizedString($htmlSafe = false,$subEntities = false) {

		require_once (DOMIT_INCLUDE_PATH.'xml_domit_utilities.php');
		global $DOMIT_defined_entities_flip;
		$result = DOMIT_Utilities::toNormalizedString($this,$subEntities,$DOMIT_defined_entities_flip);
		if($htmlSafe){
			$result = $this->forHTML($result);
		}
		return $result;
	}

}

class DOMIT_Lite_ChildNodes_Interface extends DOMIT_Lite_Node {
	function DOMIT_Lite_ChildNodes_Interface() {
		DOMIT_DOMException::raiseException(DOMIT_ABSTRACT_CLASS_INSTANTIATION_ERR,'Cannot instantiate abstract class DOMIT_Lite_ChildNodes_Interface');
	}

	function appendChild($child) {
		if(!($this->hasChildNodes())) {
			$this->childNodes[0] =$child;
			$this->firstChild = $child;
		} else {

			$index = $this->getChildNodeIndex($this->childNodes,$child);
			if($index != -1) {
				$this->removeChild($child);
			}

			$numNodes = $this->childCount;

			if($numNodes > 0)
				$prevSibling = $this->childNodes[($numNodes - 1)];
			$this->childNodes[$numNodes] = $child;


			if(isset($prevSibling)) {
				$child->previousSibling = $prevSibling;
				$prevSibling->nextSibling = $child;
			} else {
				unset($child->previousSibling);
				$child->previousSibling = null;
				$this->firstChild = $child;
			}
		}
		$this->lastChild = $child;
		$child->parentNode = $this;
		unset($child->nextSibling);
		$child->nextSibling = null;
		$child->setOwnerDocument($this);
		$this->childCount++;
		return $child;
	}

	function insertBefore($newChild,$refChild) {
		if(($refChild->nodeType == DOMIT_DOCUMENT_NODE) || ($refChild->parentNode == null)) {
			DOMIT_DOMException::raiseException(DOMIT_NOT_FOUND_ERR,'Reference child not present in the child nodes list.');
		}


		if($refChild->uid == $newChild->uid) {
			return $newChild;
		}

		$index = $this->getChildNodeIndex($this->childNodes,$newChild);
		if($index != -1) {
			$this->removeChild($newChild);
		}

		$index = $this->getChildNodeIndex($this->childNodes,$refChild);
		if($index != -1) {

			if($refChild->previousSibling != null) {
				$refChild->previousSibling->nextSibling = $newChild;
				$newChild->previousSibling = $refChild->previousSibling;
			} else {
				$this->firstChild = $newChild;
				if($newChild->previousSibling != null) {
					unset($newChild->previousSibling);
					$newChild->previousSibling = null;
				}
			}
			$newChild->parentNode = $refChild->parentNode;
			$newChild->nextSibling = $refChild;
			$refChild->previousSibling = $newChild;

			$i = $this->childCount;
			while($i >= 0) {
				if($i > $index) {
					$this->childNodes[$i] = $this->childNodes[($i - 1)];
				} else
					if($i == $index) {
						$this->childNodes[$i] = $newChild;
					}
				$i--;
			}
			$this->childCount++;
		} else {
			$this->appendChild($newChild);
		}
		$newChild->setOwnerDocument($this);
		return $newChild;
	}

	function replaceChild($newChild,$oldChild) {
		if($this->hasChildNodes()) {

			$index = $this->getChildNodeIndex($this->childNodes,$newChild);
			if($index != -1) {
				$this->removeChild($newChild);
			}

			$index = $this->getChildNodeIndex($this->childNodes,$oldChild);
			if($index != -1) {
				$newChild->ownerDocument = $oldChild->ownerDocument;
				$newChild->parentNode = $oldChild->parentNode;

				if($oldChild->previousSibling == null) {
					unset($newChild->previousSibling);
					$newChild->previousSibling = null;
				} else {
					$oldChild->previousSibling->nextSibling = $newChild;
					$newChild->previousSibling = $oldChild->previousSibling;
				}
				if($oldChild->nextSibling == null) {
					unset($newChild->nextSibling);
					$newChild->nextSibling = null;
				} else {
					$oldChild->nextSibling->previousSibling = $newChild;
					$newChild->nextSibling = $oldChild->nextSibling;
				}
				$this->childNodes[$index] = $newChild;
				if($index == 0)
					$this->firstChild = $newChild;
				if($index == ($this->childCount - 1))
					$this->lastChild = $newChild;
				$newChild->setOwnerDocument($this);
				return $newChild;
			}
		}
		DOMIT_DOMException::raiseException(DOMIT_NOT_FOUND_ERR,('Reference node for replaceChild not found.'));
	}

	function removeChild($oldChild) {
		if($this->hasChildNodes()) {

			$index = $this->getChildNodeIndex($this->childNodes,$oldChild);
			if($index != -1) {

				if(($oldChild->previousSibling != null) && ($oldChild->nextSibling != null)) {
					$oldChild->previousSibling->nextSibling = $oldChild->nextSibling;
					$oldChild->nextSibling->previousSibling = $oldChild->previousSibling;
				} else
					if(($oldChild->previousSibling != null) && ($oldChild->nextSibling == null)) {
						$this->lastChild = $oldChild->previousSibling;
						unset($oldChild->previousSibling->nextSibling);
						$oldChild->previousSibling->nextSibling = null;
					} else
						if(($oldChild->previousSibling == null) && ($oldChild->nextSibling != null)) {
							unset($oldChild->nextSibling->previousSibling);
							$oldChild->nextSibling->previousSibling = null;
							$this->firstChild = $oldChild->nextSibling;
						} else
							if(($oldChild->previousSibling == null) && ($oldChild->nextSibling == null)) {
								unset($this->firstChild);
								$this->firstChild = null;
								unset($this->lastChild);
								$this->lastChild = null;
							}
				$total = $this->childCount;

				for($i = 0; $i < $total; $i++) {
					if($i == ($total - 1)) {
						array_splice($this->childNodes,$i,1);
					} else
						if($i >= $index) {
							$this->childNodes[$i] = $this->childNodes[($i + 1)];
						}
				}
				$this->childCount--;
				$oldChild->clearReferences();
				return $oldChild;
			}
		}
		DOMIT_DOMException::raiseException(DOMIT_NOT_FOUND_ERR,('Target node for removeChild not found.'));
	}

	function &getElementsByAttribute($attrName = 'id',$attrValue = '',$returnFirstFoundNode = false,
		$treatUIDAsAttribute = false) {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_nodemaps.php');
		$nodelist = new DOMIT_NodeList();
		switch($this->nodeType) {
			case DOMIT_ELEMENT_NODE:
				$this->_getElementsByAttribute($nodelist,$attrName,$attrValue,$returnFirstFoundNode,
					$treatUIDAsAttribute);
				break;
			case DOMIT_DOCUMENT_NODE:
				if($this->documentElement != null) {
					$this->documentElement->_getElementsByAttribute($nodelist,$attrName,$attrValue,
						$returnFirstFoundNode,$treatUIDAsAttribute);
				}
				break;
		}
		if($returnFirstFoundNode) {
			if($nodelist->getLength() > 0) {
				return $nodelist->item(0);
			}
			return null;
		}
		return $nodelist;
	}

	function _getElementsByAttribute($nodelist,$attrName,$attrValue,$returnFirstFoundNode,
		$treatUIDAsAttribute,$foundNode = false) {
		if(!($foundNode && $returnFirstFoundNode)) {
			if(($this->getAttribute($attrName) == $attrValue) || ($treatUIDAsAttribute && ($attrName =='uid') && ($this->uid == $attrValue))) {
				$nodelist->appendNode($this);
				$foundNode = true;
				if($returnFirstFoundNode)
					return;
			}
			$total = $this->childCount;
			for($i = 0; $i < $total; $i++) {
				$currNode = $this->childNodes[$i];
				if($currNode->nodeType == DOMIT_ELEMENT_NODE) {
					$currNode->_getElementsByAttribute($nodelist,$attrName,$attrValue,$returnFirstFoundNode,
						$treatUIDAsAttribute,$foundNode);
				}
			}
		}
	}

}

class DOMIT_Lite_Document extends DOMIT_Lite_ChildNodes_Interface {
	var $xmlDeclaration;
	var $doctype;
	var $documentElement;
	var $parser;
	var $implementation;
	var $definedEntities = array();
	var $doResolveErrors = false;
	var $preserveWhitespace = false;
	var $doExpandEmptyElementTags = false;
	var $expandEmptyElementExceptions = array();
	var $errorCode = 0;
	var $errorString = '';
	var $httpConnection = null;
	var $doUseHTTPClient = false;
	function DOMIT_Lite_Document() {
		$this->_constructor();
		$this->xmlDeclaration = '';
		$this->doctype = '';
		$this->documentElement = null;
		$this->nodeType = DOMIT_DOCUMENT_NODE;
		$this->nodeName = '#document';
		$this->ownerDocument = $this;
		$this->parser = '';
		$this->implementation = new DOMIT_DOMImplementation();
	}

	function resolveErrors($truthVal) {
		$this->doResolveErrors = $truthVal;
	}

	function setConnection($host,$path = '/',$port = 80,$timeout = 0,$user = null,$password = null) {
		require_once (DOMIT_INCLUDE_PATH.'php_http_client_generic.php');
		$this->httpConnection = new php_http_client_generic($host,$path,$port,$timeout,
			$user,$password);
	}

	function preserveWhitespace($truthVal) {
		$this->preserveWhitespace = $truthVal;
	}

	function setAuthorization($user,$password) {
		$this->httpConnection->setAuthorization($user,$password);
	}

	function setProxyConnection($host,$path = '/',$port = 80,$timeout = 0,$user = null,
		$password = null) {
		require_once (DOMIT_INCLUDE_PATH.'php_http_proxy.php');
		$this->httpConnection = new php_http_proxy($host,$path,$port,$timeout,$user,$password);
	}

	function setProxyAuthorization($user,$password) {
		$this->httpConnection->setProxyAuthorization($user,$password);
	}

	function useHTTPClient($truthVal) {
		$this->doUseHTTPClient = $truthVal;
	}

	function getErrorCode() {
		return $this->errorCode;
	}

	function getErrorString() {
		return $this->errorString;
	}

	function expandEmptyElementTags($truthVal,$expandEmptyElementExceptions = false) {
		$this->doExpandEmptyElementTags = $truthVal;
		if(is_array($expandEmptyElementExceptions)) {
			$this->expandEmptyElementExceptions = $expandEmptyElementExceptions;
		}
	}

	function setDocumentElement($node) {
		if($node->nodeType == DOMIT_ELEMENT_NODE) {
			if($this->documentElement == null) {
				parent::appendChild($node);
			} else {
				parent::replaceChild($node,$this->documentElement);
			}
			$this->documentElement = $node;
		} else {
			DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Cannot add a node of type '.get_class($node).' as a Document Element.'));
		}
		return $node;
	}

	function appendChild($node) {
		if($node->nodeType == DOMIT_ELEMENT_NODE) {
			if($this->documentElement == null) {
				parent::appendChild($node);
				$this->setDocumentElement($node);
			} else {

				DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Cannot have more than one root node (documentElement) in a DOMIT_Document.'));
			}
		} else {
			DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Cannot add a node of type '.get_class($node).' to a DOMIT_Document.'));
		}
		return $node;
	}

	function replaceChild($newChild,$oldChild) {
		if(($this->documentElement != null) && ($oldChild->uid == $this->documentElement->uid)) {
			if($node->nodeType == DOMIT_ELEMENT_NODE) {

				$this->setDocumentElement($newChild);
			} else {
				DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Cannot replace Document Element with a node of class '.get_class($newChild)));
			}
		} else {
			if($node->nodeType == DOMIT_ELEMENT_NODE) {
				if($this->documentElement != null) {
					DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Cannot have more than one root node (documentElement) in a DOMIT_Document.'));
				} else {
					parent::replaceChild($newChild,$oldChild);
				}
			} else {
				DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Nodes of class '.get_class($newChild).' cannot be children of a DOMIT_Document.'));
			}
		}
		return $newChild;
	}

	function insertBefore($newChild,$refChild) {
		$type = $newChild->nodeType;
		if($type == DOMIT_ELEMENT_NODE) {
			if($this->documentElement == null) {
				parent::insertBefore($newChild,$refChild);
				$this->setDocumentElement($newChild);
			} else {

				DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Cannot have more than one root node (documentElement) in a DOMIT_Document.'));
			}
		} else {
			DOMIT_DOMException::raiseException(DOMIT_HIERARCHY_REQUEST_ERR,('Cannot insert a node of type '.get_class($newChild).' to a DOMIT_Document.'));
		}
		return $newChild;
	}

	function removeChild($oldChild) {
		if(($this->documentElement != null) && ($oldChild->uid == $this->documentElement->uid)) {
			parent::removeChild($oldChild);
			$this->documentElement = null;
		} else {
			parent::removeChild($oldChild);
		}
		$oldChild->clearReferences();
		return $oldChild;
	}

	function &createElement($tagName) {
		$node = new DOMIT_Lite_Element($tagName);
		$node->ownerDocument = $this;
		return $node;
	}

	function &createTextNode($data) {
		$node = new DOMIT_Lite_TextNode($data);
		$node->ownerDocument = $this;
		return $node;
	}

	function &createCDATASection($data) {
		$node = new DOMIT_Lite_CDATASection($data);
		$node->ownerDocument = $this;
		return $node;
	}

	function &getElementsByTagName($tagName) {
		$nodeList = new DOMIT_NodeList();
		if($this->documentElement != null) {
			$this->documentElement->getNamedElements($nodeList,$tagName);
		}
		return $nodeList;
	}

	function getElementsByPath($pattern,$nodeIndex = 0) {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_getelementsbypath.php');
		$gebp = new DOMIT_GetElementsByPath();
		$myResponse = $gebp->parsePattern($this,$pattern,$nodeIndex);
		return $myResponse;
	}

	function parseXML_utf8($xmlText,$useSAXY = true,$preserveCDATA = true,$fireLoadEvent = false) {
		return $this->parseXML(utf8_encode($xmlText),$useSAXY,$preserveCDATA,$fireLoadEvent);
	}

	function parseXML($xmlText,$useSAXY = true,$preserveCDATA = true,$fireLoadEvent = false) {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_utilities.php');
		if($this->doResolveErrors) {
			require_once (DOMIT_INCLUDE_PATH.'xml_domit_doctor.php');
			$xmlText = DOMIT_Doctor::fixAmpersands($xmlText);
		}
		if(DOMIT_Utilities::validateXML($xmlText)) {
			$domParser = new DOMIT_Lite_Parser();
			if($useSAXY || (!function_exists('xml_parser_create'))) {
				$this->parser = 'SAXY_LITE';
				$success = $domParser->parseSAXY($this,$xmlText,$preserveCDATA,$this->definedEntities);
			} else {
				$this->parser = 'EXPAT';
				$success = $domParser->parse($this,$xmlText,$preserveCDATA);
			}
			if($fireLoadEvent && ($this->documentElement != null)){
				$this->load($this->documentElement);
			}
			return $success;
		}
		return false;
	}

	function loadXML_utf8($filename,$useSAXY = true,$preserveCDATA = true,$fireLoadEvent = false) {
		$xmlText = $this->getTextFromFile($filename);
		return $this->parseXML_utf8($xmlText,$useSAXY,$preserveCDATA,$fireLoadEvent);
	}

	function loadXML($filename,$useSAXY = true,$preserveCDATA = true,$fireLoadEvent = false) {
		$xmlText = $this->getTextFromFile($filename);
		$xmlText = Jstring::to_utf8($xmlText);
		return $this->parseXML($xmlText,true,$preserveCDATA,$fireLoadEvent);
	}

	function establishConnection($url) {
		require_once (DOMIT_INCLUDE_PATH.'php_http_client_generic.php');
		$host = php_http_connection::formatHost($url);
		$host = substr($host,0,strpos($host,'/'));
		$this->setConnection($host);
	}

	function getTextFromFile($filename) {
		if($this->doUseHTTPClient && (substr($filename,0,5) == 'http:')) {
			$this->establishConnection($filename);
		}
		if($this->httpConnection != null) {
			$response = $this->httpConnection->get($filename);
			$this->httpConnection->disconnect();
			return $response->getResponse();
		} else
			if(function_exists('file_get_contents')) {
				return file_get_contents($filename);
			} else {
				require_once (DOMIT_INCLUDE_PATH.'php_file_utilities.php');
				$fileContents = &php_file_utilities::getDataFromFile($filename,'r');
				return $fileContents;
			}
			return '';
	}

	function saveXML_utf8($filename,$normalized = false) {
		if($normalized) {
			$stringRep = $this->toNormalizedString(false,true);

		} else {
			$stringRep = $this->toString(false,true);
		}
		return $this->saveTextToFile($filename,utf8_encode($stringRep));
	}

	function saveXML($filename,$normalized = false) {
		if($normalized) {
			$stringRep = $this->toNormalizedString(false,true);
		} else {
			$stringRep = $this->toString(false,true);
		}
		if($this->xmlDeclaration) {
			$stringRep = $this->xmlDeclaration."\n".$stringRep;
		}
		return $this->saveTextToFile($filename,$stringRep);
	}

	function saveTextToFile($filename,$text) {
		if(function_exists('file_put_contents')) {
			file_put_contents($filename,$text);
		} else {
			require_once (DOMIT_INCLUDE_PATH.'php_file_utilities.php');
			php_file_utilities::putDataToFile($filename,$text,'w');
		}
		return (file_exists($filename) && is_writable($filename));
	}

	function parsedBy() {
		return $this->parser;
	}

	function getText() {
		if($this->documentElement != null) {
			$root = $this->documentElement;
			return $root->getText();
		}
		return '';
	}

	function getDocType() {
		return $this->doctype;
	}

	function getXMLDeclaration() {
		return $this->xmlDeclaration;
	}

	function setXMLDeclaration($decl) {
		$this->xmlDeclaration = $decl;
	}

	function &getDOMImplementation() {
		return $this->implementation;
	}

	function load($contextNode) {
		$total = $contextNode->childCount;
		for($i = 0; $i < $total; $i++) {
			$currNode = $contextNode->childNodes[$i];
			$currNode->ownerDocument->load($currNode);
		}
		$contextNode->onLoad();
	}

	function getVersion() {
		return DOMIT_LITE_VERSION;
	}

	function appendEntityTranslationTable($table) {
		$this->definedEntities = $table;
		global $DOMIT_defined_entities_flip;
		$DOMIT_defined_entities_flip = array_flip($table);
	}

	function toArray() {
		$arReturn = array($this->nodeName => array());
		$total = $this->childCount;
		for($i = 0; $i < $total; $i++) {
			$arReturn[$this->nodeName][$i] = $this->childNodes[$i]->toArray();
		}
		return $arReturn;
	}

	function cloneNode($deep = false) {
		$className = get_class($this);
		$clone = new $className($this->nodeName);
		if($deep) {
			$total = $this->childCount;
			for($i = 0; $i < $total; $i++) {
				$currentChild = $this->childNodes[$i];
				$clone->appendChild($currentChild->cloneNode($deep));
			}
		}
		return $clone;
	}

	function toString($htmlSafe = false,$subEntities = false) {
		$result = '';
		$total = $this->childCount;
		for($i = 0; $i < $total; $i++) {
			$result .= $this->childNodes[$i]->toString(false,$subEntities);
		}
		if($htmlSafe)
			$result = $this->forHTML($result);
		return $result;
	}

}

class DOMIT_Lite_Element extends DOMIT_Lite_ChildNodes_Interface {
	function DOMIT_Lite_Element($tagName) {
		$this->_constructor();
		$this->nodeType = DOMIT_ELEMENT_NODE;
		$this->nodeName = $tagName;
		$this->attributes = array();
		$this->childNodes = array();
	}

	function getTagName() {
		return $this->nodeName;
	}

	function getNamedElements($nodeList,$tagName) {
		if(($this->nodeName == $tagName) || ($tagName == '*')) {
			$nodeList->appendNode($this);
		}
		$total = $this->childCount;
		for($i = 0; $i < $total; $i++) {
			$this->childNodes[$i]->getNamedElements($nodeList,$tagName);
		}
	}

	function getText() {
		$text = '';
		$numChildren = $this->childCount;
		for($i = 0; $i < $numChildren; $i++) {
			$child = $this->childNodes[$i];
			$text .= $child->getText();
		}
		return $text;
	}

	function setText($data) {
		switch($this->childCount) {
			case 1:
				if($this->firstChild->nodeType == DOMIT_TEXT_NODE) {
					$this->firstChild->setText($data);
				}
				break;
			case 0:
				$childTextNode = $this->ownerDocument->createTextNode($data);
				$this->appendChild($childTextNode);
				break;
			default:

		}
	}

	function &getElementsByTagName($tagName) {
		$nodeList = new DOMIT_NodeList();
		$this->getNamedElements($nodeList,$tagName);
		return $nodeList;
	}

	function getElementsByPath($pattern,$nodeIndex = 0) {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_getelementsbypath.php');
		$gebp = new DOMIT_GetElementsByPath();
		$myResponse = $gebp->parsePattern($this,$pattern,$nodeIndex);
		return $myResponse;
	}

	function getAttribute($name) {
		if($this->hasAttribute($name)) {
			return $this->attributes[$name];
		} else {

			return null;
		}
	}

	function setAttribute($name,$value) {
		$this->attributes[$name] = $value;
	}

	function removeAttribute($name) {
		if($this->hasAttribute($name)) {
			unset($this->attributes[$name]);
		}
	}

	function hasAttribute($name) {
		return isset($this->attributes[$name]);
	}

	function normalize() {
		if($this->hasChildNodes()) {
			$currNode = $this->childNodes[0];
			while($currNode->nextSibling != null) {
				$nextNode = $currNode->nextSibling;
				if(($currNode->nodeType == DOMIT_TEXT_NODE) && ($nextNode->nodeType ==
					DOMIT_TEXT_NODE)) {
					$currNode->nodeValue .= $nextNode->nodeValue;
					$this->removeChild($nextNode);
				} else {
					$currNode->normalize();
				}
				if($currNode->nextSibling != null) {
					$currNode = $currNode->nextSibling;
				}
			}
		}
	}

	function toArray() {
		$arReturn = array($this->nodeName => array("attributes" => $this->attributes));
		$total = $this->childCount;
		for($i = 0; $i < $total; $i++) {
			$arReturn[$this->nodeName][$i] = $this->childNodes[$i]->toArray();
		}
		return $arReturn;
	}

	function cloneNode($deep = false) {
		$className = get_class($this);
		$clone = new $className($this->nodeName);
		$clone->attributes = $this->attributes;
		if($deep) {
			$total = $this->childCount;
			for($i = 0; $i < $total; $i++) {
				$currentChild = $this->childNodes[$i];
				$clone->appendChild($currentChild->cloneNode($deep));
			}
		}
		return $clone;
	}

	function toString($htmlSafe = false,$subEntities = false) {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_utilities.php');
		global $DOMIT_defined_entities_flip;
		$result = '<'.$this->nodeName;

		foreach($this->attributes as $key => $value) {
			$result .= ' '.$key.'="';
			$result .= $subEntities?DOMIT_Utilities::convertEntities($value,$DOMIT_defined_entities_flip):
				$value;
			$result .= '"';
		}

		$myNodes = $this->childNodes;
		$total = count($myNodes);
		if($total != 0) {
			$result .= '>';
			for($i = 0; $i < $total; $i++) {
				$child = $myNodes[$i];
				$result .= $child->toString(false,$subEntities);
			}
			$result .= '</'.$this->nodeName.'>';
		} else {
			if($this->ownerDocument->doExpandEmptyElementTags) {
				if(in_array($this->nodeName,$this->ownerDocument->expandEmptyElementExceptions)) {
					$result .= ' />';
				} else {
					$result .= '></'.$this->nodeName.'>';
				}
			} else {
				if(in_array($this->nodeName,$this->ownerDocument->expandEmptyElementExceptions)) {
					$result .= '></'.$this->nodeName.'>';
				} else {
					$result .= ' />';
				}
			}
		}
		if($htmlSafe)
			$result = $this->forHTML($result);
		return $result;
	}

}

class DOMIT_Lite_TextNode extends DOMIT_Lite_Node {
	function DOMIT_Lite_TextNode($data) {
		$this->_constructor();
		$this->nodeType = DOMIT_TEXT_NODE;
		$this->nodeName = '#text';
		$this->setText($data);
	}

	function getText() {
		return $this->nodeValue;
	}

	function setText($data) {
		$this->nodeValue = $data;
	}

	function toArray() {
		return $this->toString();
	}

	function cloneNode($deep=false) {
		$className = get_class($this);
		$clone = new $className($this->nodeValue);
		return $clone;
	}

	function toString($htmlSafe = false,$subEntities = false) {
		require_once (DOMIT_INCLUDE_PATH.'xml_domit_utilities.php');
		global $DOMIT_defined_entities_flip;
		$result = $subEntities?DOMIT_Utilities::convertEntities($this->nodeValue,$DOMIT_defined_entities_flip):
			$this->nodeValue;
		if($htmlSafe)
			$result = $this->forHTML($result);
		return $result;
	}

}

class DOMIT_Lite_CDATASection extends DOMIT_Lite_TextNode {
	function DOMIT_Lite_CDATASection($data) {
		$this->_constructor();
		$this->nodeType = DOMIT_CDATA_SECTION_NODE;
		$this->nodeName = '#cdata-section';
		$this->setText($data);
	}

	function toString($htmlSafe = false,$subEntities = false) {
		$result = '<![CDATA[';
		$result .= $subEntities?str_replace("]]>","]]&gt;",$this->nodeValue):$this->nodeValue;
		$result .= ']]>';
		if($htmlSafe)
			$result = $this->forHTML($result);
		return $result;
	}

}

class DOMIT_Lite_Parser {
	var $xmlDoc = null;
	var $currentNode = null;
	var $lastChild = null;
	var $inCDATASection = false;

	var $inTextNode = false;
	var $preserveCDATA;
	var $parseContainer = '';
	var $parseItem = '';
	function parse($myXMLDoc,$xmlText,$preserveCDATA = true) {
		$this->xmlDoc = $myXMLDoc;
		$this->lastChild = $this->xmlDoc;
		$this->preserveCDATA = $preserveCDATA;

		if(version_compare(phpversion(),'5.0','<=')) {
			$parser = xml_parser_create('');
		} else {
			$parser = xml_parser_create('iso-8859-1');
		}

		xml_set_object($parser,$this);
		xml_set_element_handler($parser,'startElement','endElement');
		xml_set_character_data_handler($parser,'dataElement');
		xml_set_default_handler($parser,'defaultDataElement');
		xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
		if(!$this->xmlDoc->preserveWhitespace) {
			xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
		} else {
			xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,0);
		}


		if(!$this->xmlDoc->preserveWhitespace) {
			$xmlText = preg_replace('/>'."[[:space:]]+".'</iu','><',$xmlText);
		}
		$success = xml_parse($parser,$xmlText);
		$this->xmlDoc->errorCode = xml_get_error_code($parser);
		$this->xmlDoc->errorString = xml_error_string($this->xmlDoc->errorCode);
		xml_parser_free($parser);
		return $success;
	}

	function parseSAXY($myXMLDoc,$xmlText,$preserveCDATA,$definedEntities) {
		require_once (DOMIT_INCLUDE_PATH.'xml_saxy_lite_parser.php');
		$this->xmlDoc = $myXMLDoc;
		$this->lastChild = $this->xmlDoc;

		$parser = new SAXY_Lite_Parser();
		$parser->appendEntityTranslationTable($definedEntities);

		$parser->preserveWhitespace = $this->xmlDoc->preserveWhitespace;
		$parser->xml_set_element_handler(array($this,'startElement'),array($this,'endElement'));
		$parser->xml_set_character_data_handler(array($this,'dataElement'));
		if($preserveCDATA) {
			$parser->xml_set_cdata_section_handler(array($this,'cdataElement'));
		}
		$success = $parser->parse($xmlText);
		$this->xmlDoc->errorCode = $parser->xml_get_error_code();
		$this->xmlDoc->errorString = $parser->xml_error_string($this->xmlDoc->errorCode);
		return $success;
	}

	function dumpTextNode() {

		$currentNode = $this->xmlDoc->createTextNode($this->parseContainer);
		$this->lastChild->appendChild($currentNode);
		$this->inTextNode = false;
		$this->parseContainer = '';
	}

	function startElement($parser,$name,$attrs) {
		if($this->inTextNode) {
			$this->dumpTextNode();
		}
		$currentNode = $this->xmlDoc->createElement($name);
		$currentNode->attributes = $attrs;
		$this->lastChild->appendChild($currentNode);
		$this->lastChild = $currentNode;
	}

	function endElement($parser,$name) {
		if($this->inTextNode) {
			$this->dumpTextNode();
		}
		$this->lastChild = $this->lastChild->parentNode;
	}

	function dataElement($parser,$data) {
		if(!$this->inCDATASection)
			$this->inTextNode = true;
		$this->parseContainer .= $data;
	}

	function cdataElement($parser,$data) {
		$currentNode = $this->xmlDoc->createCDATASection($data);
		$this->lastChild->appendChild($currentNode);
	}

	function defaultDataElement($parser,$data) {
		if(strlen($data) > 2) {
			$pre = strtoupper(substr($data,0,3));
			switch($pre) {
				case '<![':

					if($this->preserveCDATA) {
						$this->inCDATASection = true;
					}
					break;
				case ']]>':

					if($this->preserveCDATA) {
						$currentNode = $this->xmlDoc->createCDATASection($this->parseContainer);
						$this->lastChild->appendChild($currentNode);
						$this->inCDATASection = false;
						$this->parseContainer = '';
					} else {
						$this->dumpTextNode();
					}
					break;
			}
		}
	}
}
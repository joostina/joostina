<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
defined('_JOOS_CORE') or die();

class InputFilter {

	private static $instance;
	protected $tagsArray;
	protected $attrArray;
	protected $tagsMethod;
	protected $attrMethod;
	protected $xssAuto;
	protected $tagBlacklist = array('applet', 'body', 'bgsound', 'base', 'basefont', 'embed', 'frame', 'frameset', 'head', 'html', 'id', 'iframe', 'ilayer', 'layer', 'link', 'meta', 'name', 'object', 'script', 'style', 'title', 'xml');
	protected $attrBlacklist = array('action', 'background', 'codebase', 'dynsrc', 'lowsrc');

	private function __construct($tagsArray = array(), $attrArray = array(), $tagsMethod = 0, $attrMethod = 0, $xssAuto = 1) {
		$tagsArray = array_map('strtolower', (array) $tagsArray);
		$attrArray = array_map('strtolower', (array) $attrArray);
		$this->tagsArray = (array) $tagsArray;
		$this->attrArray = (array) $attrArray;
		$this->tagsMethod = $tagsMethod;
		$this->attrMethod = $attrMethod;
		$this->xssAuto = $xssAuto;
	}

	public static function instance($tagsArray = array(), $attrArray = array(), $tagsMethod = 0, $attrMethod = 0, $xssAuto = 1) {

		!JDEBUG ? : jd_inc('InputFilter::instance');
		
		if (self::$instance === null) {
			self::$instance = new self($tagsArray, $attrArray, $tagsMethod, $attrMethod, $xssAuto);
		}

		return self::$instance;
	}

	private function __clone() {
		
	}

	public function process($source) {
		if (is_array($source)) {
			foreach ($source as $key => $value) {
				if (is_string($value)) {
					$source[$key] = $this->remove($this->decode($value));
				}
			}
			return $source;
		} else {
			if (is_string($source) && !empty($source)) {
				return $this->remove($this->decode($source));
			} else {
				return $source;
			}
		}
	}

	protected function remove($source) {
		//$loopCounter = 0;
		while ($source != $this->filterTags($source)) {
			$source = $this->filterTags($source);
			//$loopCounter++;
		}
		return $source;
	}

	protected function filterTags($source) {
		$preTag = null;
		$postTag = $source;
		$tagOpen_start = strpos($source, '<');
		while ($tagOpen_start !== false) {
			$preTag .= substr($postTag, 0, $tagOpen_start);
			$postTag = substr($postTag, $tagOpen_start);
			$fromTagOpen = substr($postTag, 1);
			$tagOpen_end = strpos($fromTagOpen, '>');
			if ($tagOpen_end === false) {
				$postTag = substr($postTag, $tagOpen_start + 1);
				$tagOpen_start = strpos($postTag, '<');
				continue;
			}
			$tagOpen_nested = strpos($fromTagOpen, '<');
			//$tagOpen_nested_end = strpos(substr($postTag,$tagOpen_end),'>');
			if (($tagOpen_nested !== false) && ($tagOpen_nested < $tagOpen_end)) {
				$preTag .= substr($postTag, 0, ($tagOpen_nested + 1));
				$postTag = substr($postTag, ($tagOpen_nested + 1));
				$tagOpen_start = strpos($postTag, '<');
				continue;
			}
			$tagOpen_nested = (strpos($fromTagOpen, '<') + $tagOpen_start + 1);
			$currentTag = substr($fromTagOpen, 0, $tagOpen_end);
			$tagLength = strlen($currentTag);
			$tagLeft = $currentTag;
			$attrSet = array();
			$currentSpace = strpos($tagLeft, ' ');
			if (substr($currentTag, 0, 1) == "/") {
				$isCloseTag = true;
				list($tagName) = explode(' ', $currentTag);
				$tagName = substr($tagName, 1);
			} else {
				$isCloseTag = false;
				list($tagName) = explode(' ', $currentTag);
			}
			if ((!preg_match("/^[a-z][a-z0-9]*$/iu", $tagName)) || (!$tagName) || ((in_array(strtolower($tagName), $this->tagBlacklist)) && ($this->xssAuto))) {
				$postTag = substr($postTag, ($tagLength + 2));
				$tagOpen_start = strpos($postTag, '<');
				continue;
			}
			while ($currentSpace !== false) {
				$fromSpace = substr($tagLeft, ($currentSpace + 1));
				$nextSpace = strpos($fromSpace, ' ');
				$openQuotes = strpos($fromSpace, '"');
				$closeQuotes = strpos(substr($fromSpace, ($openQuotes + 1)), '"') + $openQuotes +
						1;
				if (strpos($fromSpace, '=') !== false) {
					if (($openQuotes !== false) && (strpos(substr($fromSpace, ($openQuotes + 1)), '"')
							!== false)) {
						$attr = substr($fromSpace, 0, ($closeQuotes + 1));
					} else {
						$attr = substr($fromSpace, 0, $nextSpace);
					}
				} else {
					$attr = substr($fromSpace, 0, $nextSpace);
				}
				if (!$attr) {
					$attr = $fromSpace;
				}
				$attrSet[] = $attr;
				$tagLeft = substr($fromSpace, strlen($attr));
				$currentSpace = strpos($tagLeft, ' ');
			}
			$tagFound = in_array(strtolower($tagName), $this->tagsArray);
			if ((!$tagFound && $this->tagsMethod) || ($tagFound && !$this->tagsMethod)) {
				if (!$isCloseTag) {
					$attrSet = $this->filterAttr($attrSet);
					$preTag .= '<' . $tagName;
					for ($i = 0; $i < count($attrSet); $i++) {
						$preTag .= ' ' . $attrSet[$i];
					}
					if (strpos($fromTagOpen, "</" . $tagName)) {
						$preTag .= '>';
					} else {
						$preTag .= ' />';
					}
				} else {
					$preTag .= '</' . $tagName . '>';
				}
			}
			$postTag = substr($postTag, ($tagLength + 2));
			$tagOpen_start = strpos($postTag, '<');
		}
		if ($postTag != '<') {
			$preTag .= $postTag;
		}
		return $preTag;
	}

	protected function filterAttr($attrSet) {
		$newSet = array();
		for ($i = 0; $i < count($attrSet); $i++) {
			if (!$attrSet[$i]) {
				continue;
			}
			$attrSubSet = explode('=', trim($attrSet[$i]), 2);
			list($attrSubSet[0]) = explode(' ', $attrSubSet[0]);
			// TODO eregi - зло
			if ((!eregi("^[a-z]*$", $attrSubSet[0])) || (($this->xssAuto) && ((in_array(strtolower($attrSubSet[0]), $this->attrBlacklist)) || (substr(strtolower($attrSubSet[0]), 0, 2) == 'on')))) {
				continue;
			}
			if ($attrSubSet[1]) {
				$attrSubSet[1] = str_replace('&#', '', $attrSubSet[1]);
				$attrSubSet[1] = preg_replace('/\s+/', '', $attrSubSet[1]);
				$attrSubSet[1] = str_replace('"', '', $attrSubSet[1]);
				if ((substr($attrSubSet[1], 0, 1) == "'") && (substr($attrSubSet[1], (strlen($attrSubSet[1]) -
								1), 1) == "'")) {
					$attrSubSet[1] = substr($attrSubSet[1], 1, (strlen($attrSubSet[1]) - 2));
				}
				$attrSubSet[1] = stripslashes($attrSubSet[1]);
			}
			if (self::badAttributeValue($attrSubSet)) {
				continue;
			}
			$attrFound = in_array(strtolower($attrSubSet[0]), $this->attrArray);
			if ((!$attrFound && $this->attrMethod) || ($attrFound && !$this->attrMethod)) {
				if ($attrSubSet[1]) {
					$newSet[] = $attrSubSet[0] . '="' . $attrSubSet[1] . '"';
				} elseif ($attrSubSet[1] == "0") {
					$newSet[] = $attrSubSet[0] . '="0"';
				} else {
					$newSet[] = $attrSubSet[0] . '="' . $attrSubSet[0] . '"';
				}
			}
		}
		return $newSet;
	}

	public function badAttributeValue($attrSubSet) {
		$attrSubSet[0] = strtolower($attrSubSet[0]);
		$attrSubSet[1] = strtolower($attrSubSet[1]);
		return (((strpos($attrSubSet[1], 'expression') !== false) && ($attrSubSet[0]) == 'style') || (strpos($attrSubSet[1], 'javascript:') !== false) || (strpos($attrSubSet[1], 'behaviour:') !== false) || (strpos($attrSubSet[1], 'vbscript:') !== false) || (strpos($attrSubSet[1], 'mocha:') !== false) || (strpos($attrSubSet[1], 'livescript:') !== false));
	}

	protected function decode($source) {
		$source = html_entity_decode($source, ENT_QUOTES, "UTF-8");
		$source = preg_replace('/&#(\d+);/me', "chr(\\1)", $source);
		$source = preg_replace('/&#x([a-f0-9]+);/mei', "chr(0x\\1)", $source);
		return $source;
	}

	public function safeSQL($source) {
		if (is_array($source)) {
			foreach ($source as $key => $value) {
				if (is_string($value)) {
					$source[$key] = $this->quoteSmart($this->decode($value));
				}
			}
			return $source;
		} else
		if (is_string($source)) {
			if (is_string($source)) {
				return $this->quoteSmart($this->decode($source));
			}
		} else {
			return $source;
		}
		return 'error';
	}

	protected function quoteSmart($source) {
		$source = $this->escapeString($source);
		return $source;
	}

	protected function escapeString($string) {
		$string = mysql_real_escape_string($string);
		return $string;
	}

}
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
/*
  function test($param) {
  throw new MyException("Error");
  }

  try {
  test(isset($_GET['var']) ? $_GET['var'] : 0);
  } catch (Exception $e) {
  echo $e;
  }
 */

// http://alexmuz.ru/php-exception-code/
class MyException extends Exception {
	const CONTEXT_RADIUS = 5;

	private function getFileContext() {
		$file = $this->getFile();
		$line_number = $this->getLine();
		$context = array();
		$i = 0;
		foreach (file($file) as $line) {
			$i++;
			if ($i >= $line_number - self::CONTEXT_RADIUS && $i <= $line_number + self::CONTEXT_RADIUS) {
				if ($i == $line_number) {
					$context[] = ' >>' . $i . "\t" . $line;
				} else {
					$context[] = '   ' . $i . "\t" . $line;
				}
			}
			if ($i > $line_number + self::CONTEXT_RADIUS)
				break;
		}
		return "\n" . implode("", $context);
	}

	public function __toString() {
		$message = nl2br($this->getMessage());
		$code = highlightPHP($this->getFileContext());
		$result = <<<HTML
  <style>
    body { background-color: #fff; color: #333; }
    body, p, ol, ul, td { font-family: verdana, arial, helvetica, sans-serif; font-size: 13px; line-height: 25px; }
    pre { background-color: #eee; padding: 10px; font-size: 11px; line-height: 18px; }
    a { color: #000; }
    a:visited { color: #666; }
    a:hover { color: #fff; background-color:#000; }
  </style>
<div style="width:99%; position:relative">
<h2 id='Title'>{$message}</h2>
<div id="Context" style="display: block;"><h3>Error with code {$this->getCode()} in '{$this->getFile()}' around line {$this->getLine()}:</h3>{$code}</div>
<div id="Trace"><h2>Call stack</h2><pre>{$this->getTraceAsString()}</pre></div>
<div id="Request"><h2>Request</h2><pre>
HTML;
		$result .= var_export($_REQUEST, true);
		
		if (ob_get_level() !== 0) {
			ob_clean();
		}
		echo $result .= "</pre></div></div>";
		die();
		return $result;
	}

}

function highlightPHP($str) {
	$str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);
	$str = str_replace(array('&lt;?php', '?&gt;', '\\'), array('phptagopen', 'phptagclose', 'backslashtmp'), $str);
	$str = '<?php //tempstart' . "\n" . $str . '//tempend ?>';
	$str = highlight_string($str, true);
	$str = preg_replace("#\<code\>.+?//tempstart\<br />\</span\>#is", "<code>\n", $str);
	$str = preg_replace("#\<code\>.+?//tempstart\<br />#is", "<code>\n", $str);
	$str = preg_replace("#//tempend.+#is", "</span>\n</code>", $str);
	$str = str_replace(array('phptagopen', 'phptagclose', 'backslashtmp'), array('&lt;?php', '?&gt;', '\\'), $str);
	return $str;
}
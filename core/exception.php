<?php

/**
 * Автозагрузчик классов
 *
 * @package Joostina.Core
 * @author JoostinaTeam
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version SVN: $Id: joostina.php 238 2011-03-13 13:24:57Z LeeHarvey $
 * Иинформация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

// на основе http://alexmuz.ru/php-exception-code/
class joosException extends Exception {
	const CONTEXT_RADIUS = 5;
/*
	public function __construct($msg = '', $code = 0, Exception $previous = null) {
		parent::__construct($msg, (int) $code, $previous);
		echo $this->__toString();
	}
*/
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
		// очистим всю вышестоящую буферизацию без вывода её в браузер
		if(ob_get_level() ){
			ob_end_clean();
		}
		parent::__toString();
		echo html_entity_decode($this->create());
		die();
	}


	public function create() {

		header('Content-type: text/html; charset=UTF-8');

		$message = nl2br($this->getMessage());
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
<div id="Context" style="display: block;"><h3>Error with code {$this->getCode()} in '{$this->getFile()}' around line {$this->getLine()}:</h3><pre>{$this->getFileContext()}</pre></div>
<div id="Trace"><h2>Call stack</h2><pre>{$this->getTraceAsString()}</pre></div>
HTML;
		$result .= "</div></div>";
		return $result;
	}

}

/*
function PHP_errorHandler($errno, $errmsg, $filename, $linenum, $errcontext = NULL) {
	throw new joosException($errmsg, 0);
	//throw new joosException($errmsg, 0, $errno, $filename, $linenum);
}

if (set_error_handler("PHP_errorHandler", E_ALL & ~E_DEPRECATED) === false) {
	die(__('Обработчик ошибок не зарегистрирован'));
};

// обработка фатальных ошибок
register_shutdown_function('shutdown');

//only call this if debug on
function shutdown() {
	$isError = false;
	if (($error = error_get_last())) {
		switch ($error['type']) {
			case E_ERROR:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_USER_ERROR:
				$isError = true;
				break;
		}
	}
	if ($isError) {
		PHP_errorHandler('Fatal', $error['message'], $error['file'], $error['line'], $error['type']);
	}
}
 */
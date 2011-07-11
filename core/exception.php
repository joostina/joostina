<?php

/**
 * Автозагрузчик классов
 *
 * @package Joostina.Core
 * @author JoostinaTeam
 * @copyright (C) 2007-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version SVN: $Id: joostina.php 238 2011-03-13 13:24:57Z LeeHarvey $
 * Иинформация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

set_error_handler('joosErrorHandler');
register_shutdown_function('joosfatalErrorShutdownHandler');

//set_exception_handler(array( new joosException , 'handle'));

function joosErrorHandler($code, $message, $file, $line) {
	throw new joosException(__('Ошибка :message! <br /> Код: <pre>:code</pre> Файл: :error_file<br />Строка :error_line'),
			array(':message' => $message, ':code' => $code, ':error_file' => $file, ':error_line' => $line)
	);
}

function joosfatalErrorShutdownHandler() {
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR) {
		joosErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}

// на основе http://alexmuz.ru/php-exception-code/
class joosException extends Exception {
	const CONTEXT_RADIUS = 10;

	public function __construct($message='', array $params = array()) {
		parent::__construct(strtr($message, $params));

		if (isset($params[':error_file'])) {
			$this->file = $params[':error_file'];
		}

		if (isset($params[':error_line'])) {
			$this->line = $params[':error_line'];
		}

		if (isset($params[':error_code'])) {
			$this->code = $params[':error_code'];
		}


		$this->__toString();
	}

	private function get_file_context() {

		$file = $this->getFile();
		$line_number = $this->getLine();

		$context = array();
		$i = 0;
		foreach (file($file) as $line) {
			$i++;
			if ($i >= $line_number - self::CONTEXT_RADIUS && $i <= $line_number + self::CONTEXT_RADIUS) {
				if ($i == $line_number) {
					$context[] = ' >>   ' . $i . "\t" . $line;
				} else {
					$context[] = "\t" . $i . "\t" . $line;
				}
			}
			if ($i > $line_number + self::CONTEXT_RADIUS) {
				break;
			}
		}
		return "\n" . implode("", $context);
	}

	public function __toString() {
		// очистим всю вышестоящую буферизацию без вывода её в браузер
		!ob_get_level() ? : ob_end_clean();

		parent::__toString();
		echo joosRequest::is_ajax() ? $this->to_json() : $this->create();
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
<div id="Context" style="display: block;"><h3>Ошибка с кодом {$this->getCode()} в файле '{$this->getFile()}' в строке {$this->getLine()}:</h3><pre>{$this->prepare($this->get_file_context())}</pre></div>
<div id="Trace"><h2>Стэк вызовов</h2><pre>{$this->getTraceAsString()}</pre></div>
HTML;
		$result .= "</div></div>";
		return $result;
	}

	protected function prepare($content) {
		return joosFilter::htmlspecialchars($content);
	}

	/**
	 * Возврат информации об ошибки в JSON-сериализованном виде
	 * 
	 * @return json string строка с кодом ошибки закодированная в JSON
	 */
	private function to_json() {
		$response = array('code' => ($this->getCode() != 0) ? $this->getCode() : 500, 'message' => $this->getMessage());

		return json_encode($response);
	}

	private static function error_email() {

		// e-mail headers
		$headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-15\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: SpoonLibrary Webmail\n";
		$headers .= "From: Spoon Library <no-reply@spoon-library.com>\n";

		// send email
		@mail(SPOON_DEBUG_EMAIL, 'Exception Occured', $output, $headers);
	}

}

<?php

function dump($var, $p_bThrowException = false) {
	if ($p_bThrowException === false) {
		$trace = debug_backtrace();
		$label = "File: " . str_replace($_SERVER['DOCUMENT_ROOT'], '...', $trace[0]['file'] . "<br>\n");
		$label .= "Line: {$trace[0]['line']}" . "<br>\n";
		if (isset($trace[1])) {
			$label .= "Function: {$trace[1]['function']}<br>\n";
		} else {
			$label .= "Global (not in function)<br>\n";
		}
		$html = $label . '<pre>';
		echo $html;
		var_dump($var);
		echo '</pre>';
	} else {
		echo "<pre>";
		var_dump($var);
		throw new joosException('Exceptioned Dump');
	}
}
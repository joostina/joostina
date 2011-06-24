<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

echo '<h1>Внутреннее тестирование системных функций</h1>';

foreach ($results as $function => $function_results) {
	echo sprintf('<h3>Использование: <strong>%s</strong></h3>', $function);
	echo '<ul>';
	foreach ($function_results as $function_sample => $result) {
		$result = $result ? : 'Ошибок нет - всё ок, функция ничего не возвращает из себя';
		
		echo '<li>';
		echo sprintf('Синтаксис: <strong>%s</strong><br />Результат: %s<br /><br />', $function_sample, $result);
		echo '</li>';
	}
	echo '</ul>';
}
<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

echo '<h1>Внутреннее тестирование системных функций</h1>';

foreach ($results as $function => $function_results) {
	echo sprintf('<h3>Использование: <strong>%s</strong></h3>', $function);
	echo '<ul>';
	foreach ($function_results as $function_sample => $result) {
		
		// возвратила ли функция результат
		$result = $result===null ? 'Ошибок нет - всё ок, функция ничего не возвращает из себя' : $result;

		// если результат - массив
		if (is_array($result)) {
			$result = var_export($result, true);
		}

		// если результат - булево значение
		if (is_bool($result)) {
			$result = $result ? '(bool) TRUE' : '(bool) FALSE';
		}

		$result = sprintf('<pre><code>%s</code></pre>', $result, true);

		echo '<li>';
		echo sprintf('Синтаксис: <strong>%s</strong> результат: %s<br /><br />', $function_sample, $result);
		echo '</li>';
	}
	echo '</ul>';
}
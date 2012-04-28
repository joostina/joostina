<?php

defined('_JOOS_CORE') or exit();

/**
 * Автоматический сборщик классов системы и их расположения
 *
 * @version    1.0
 * @package    Core\Libraries
 * @subpackage RobotLoader
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * Оригинальная идея базируется на разработках Nette FW https://github.com/nette/nette/blob/master/Nette/Loaders/RobotLoader.php
 * */
class joosRobotLoader {

	public static function get_classes($location) {

		$directory = new RecursiveDirectoryIterator($location);
		$iterator = new RecursiveIteratorIterator($directory);
		$regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

		$classes = array();
		foreach ($regex as $path) {

			$expected = FALSE;
			$level = $minLevel = 0;

			$name = '';

			$file = $path[0];

			if (!joosFile::is_readable($file)) {
				continue;
			}

			$php_file_source = file_get_contents($file);

			$class_location = str_replace(JPATH_BASE . DS, '', $file);

			foreach (@token_get_all($php_file_source) as $token) {
				if (is_array($token)) {
					switch ($token[0]) {
						case T_COMMENT:
						case T_DOC_COMMENT:
						case T_WHITESPACE:
							continue 2;

						case T_NS_SEPARATOR:
						case T_STRING:
							if ($expected) {
								$name .= $token[1];
							}
							continue 2;


						case T_CLASS:
						case T_INTERFACE:
							$expected = $token[0];
							$name = '';
							continue 2;
						case T_CURLY_OPEN:
						case T_DOLLAR_OPEN_CURLY_BRACES:
							$level++;
					}
				}

				if ($expected) {
					switch ($expected) {
						case T_CLASS:
						case T_INTERFACE:
							if ($level === $minLevel) {
								$classes[$name] = $class_location;
							}
							break;
					}

					$expected = NULL;
				}

				if ($token === '{') {
					$level++;
				} elseif ($token === '}') {
					$level--;
				}
			}
		}

		ksort($classes);

		return $classes;
	}

}

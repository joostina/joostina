<?php

defined('_JOOS_CORE') or die;

require_once __DIR__ . DS . 'autoload.php';
require_once __DIR__ . DS . 'logger' . DS . 'handler.php';

joosLoggingAutoloader::register();

/**
 * Модуль логирования данных на основе библиотеки Monolog
 * Настраивается в общем конфигурационном файле, в сексии logging. Пример настройки:
 *
 * <pre>
 * 	'logging'	=> array(
 * 		'debug'	=> array(
 * 			'stream'	=> array(
 * 				'level'		=> joosLoggingLevels::DEBUG,
 * 				'stream'	=> JPATH_BASE.DS.'logs'.DS.'example.log'
 * 			),
 * 			'mail'		=> array(
 * 				'level'		=> joosLoggingLevels::ERROR,
 * 				'to'		=> 'mail@example.com',
 * 				'subject'	=> 'Message from monolog',
 * 				'from'		=> 'mail@example.com'
 * 			)
 * 		)
 * 	)
 * </pre>
 *
 * @author Maxim Format
 */
class joosLogging {

	protected static $_instance = array();

	/**
	 * Получение экземпляра логгера
	 *
	 * @static
	 * @param string $name Имя экземпляра логгера. На основе него берутся настройки из конфигурационного файла
	 * @return joosLogging
	 */
	public static function instance($name = 'default') {
		if (empty(self::$_instance[$name])) {
			self::$_instance[$name] = new joosLogging($name);
		}

		return self::$_instance[$name];
	}

	protected $_logger = NULL;

	/**
	 * Настройки берутся из секции-пресета, вложенного в сексию logging конфигурационного файла
	 *
	 * @param string $name
	 */
	public function __construct($name) {
		$this->_logger = new \Monolog\Logger($name);

		joosConfig::init();
		$config = joosConfig::get2('logging', $name);
		foreach ($config as $handler => $options) {
			$this->_logger->pushHandler(joosLoggingHandler::factory($handler, $options)->handler());
		}
	}

	/**
	 * Добавление произвольного сообщения в лог.
	 * Level задает уровень обработки сообщения, указываемого в кофигурационном файле.
	 * По умолчанию равен DEBUG, что соответствует передаче сообщания обработчикам, которые настроены на обработку
	 * сообщений, с этим уровнем
	 *
	 * @param string $message
	 * @param int $level
	 */
	public function add($message, $level = joosLoggingLevels::DEBUG) {
		$this->_logger->addRecord($level, $message);
	}

	/**
	 * Добавление сообщения в debug-лог и его обработка обработчиками, настроенными на этот уровень
	 *
	 * @param string $message
	 */
	public function debug($message) {
		$this->add($message, joosLoggingLevels::DEBUG);
	}

	/**
	 * Добавление сообщения в info-лог и его обработка обработчиками, настроенными на этот уровень
	 *
	 * @param string $message
	 */
	public function info($message) {
		$this->add($message, joosLoggingLevels::INFO);
	}

	/**
	 * Добавление сообщения в warning-лог и его обработка обработчиками, настроенными на этот уровень
	 *
	 * @param string $message
	 */
	public function warning($message) {
		$this->add($message, joosLoggingLevels::WARNING);
	}

	/**
	 * Добавление сообщения в error-лог и его обработка обработчиками, настроенными на этот уровень
	 *
	 * @param string $message
	 */
	public function error($message) {
		$this->add($message, joosLoggingLevels::ERROR);
	}

	/**
	 * Добавление сообщения в critical-error-лог и его обработка обработчиками, настроенными на этот уровень
	 *
	 * @param string $message
	 */
	public function critical($message) {
		$this->add($message, joosLoggingLevels::CRITICAL);
	}

	/**
	 * Добавление сообщения в alert-лог и его обработка обработчиками, настроенными на этот уровень
	 *
	 * @param string $message
	 */
	public function alert($message) {
		$this->add($message, joosLoggingLevels::ALERT);
	}

}

/**
 * Обертка над классом \Monolog\Logging для удобного использования
 */
class joosLoggingLevels {
	/**
	 * Detailed debug information
	 */

	const DEBUG = 100;

	/**
	 * Interesting events
	 *
	 * Examples: User logs in, SQL logs.
	 */
	const INFO = 200;

	/**
	 * Exceptional occurences that are not errors
	 *
	 * Examples: Use of deprecated APIs, poor use of an API,
	 * undesirable things that are not necessarily wrong.
	 */
	const WARNING = 300;

	/**
	 * Runtime errors
	 */
	const ERROR = 400;

	/**
	 * Critical conditions
	 *
	 * Example: Application component unavailable, unexpected exception.
	 */
	const CRITICAL = 500;

	/**
	 * Action must be taken immediately
	 *
	 * Example: Entire website down, database unavailable, etc.
	 * This should trigger the SMS alerts and wake you up.
	 */
	const ALERT = 550;

}
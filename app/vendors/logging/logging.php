<?php

defined('_JOOS_CORE') or die;

require_once __DIR__ . '/Monolog/Logger.php';
require_once __DIR__ . '/Monolog/Formatter/FormatterInterface.php';
require_once __DIR__ . '/Monolog/Formatter/WildfireFormatter.php';
require_once __DIR__ . '/Monolog/Formatter/LineFormatter.php';
require_once __DIR__ . '/Monolog/Handler/HandlerInterface.php';
require_once __DIR__ . '/Monolog/Handler/AbstractHandler.php';
require_once __DIR__ . '/Monolog/Handler/AbstractProcessingHandler.php';
require_once __DIR__ . '/Monolog/Handler/StreamHandler.php';
require_once __DIR__ . '/Monolog/Handler/FirePHPHandler.php';

class joosLogging {

	protected static $_instance	= array();

	/**
	 * @static
	 * @param string $name
	 * @return joosLogging
	 */
	public static function instance($name = 'default')
	{
		if (empty(self::$_instance[$name]))
		{
			self::$_instance[$name]	= new joosLogging($name);
		}

		return self::$_instance[$name];
	}

	protected $_logger	= NULL;

	public function __construct($name)
	{
		$this->_logger	= new \Monolog\Logger($name);

		$this->_logger->pushHandler(new \Monolog\Handler\StreamHandler($this->get_log_path($name)));
	}

	protected function get_log_path($name)
	{
		$path	= JPATH_BASE.DS.'logs';
		if ( ! is_dir($path))
		{
			mkdir($path);
		}

		$path	.= DS.$name.'-'.date('y-m-d').'.log';

		return $path;
	}

	public function add($message, $level = josLoggingLevels::DEBUG)
	{
		$this->_logger->addRecord($level, $message);
	}

	public function debug($message)
	{
		$this->add($message, josLoggingLevels::DEBUG);
	}

	public function info($message)
	{
		$this->add($message, josLoggingLevels::INFO);
	}

	public function warning($message)
	{
		$this->add($message, josLoggingLevels::WARNING);
	}

	public function error($message)
	{
		$this->add($message, josLoggingLevels::ERROR);
	}

	public function critical($message)
	{
		$this->add($message, josLoggingLevels::CRITICAL);
	}

	public function alert($message)
	{
		$this->add($message, josLoggingLevels::ALERT);
	}

}

class josLoggingLevels {

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
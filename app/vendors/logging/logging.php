<?php

defined('_JOOS_CORE') or die;

require_once __DIR__ . '/Monolog/Logger.php';
require_once __DIR__ . '/logger/handler.php';
require_once __DIR__ . '/Monolog/Formatter/FormatterInterface.php';
require_once __DIR__ . '/Monolog/Formatter/LineFormatter.php';
require_once __DIR__ . '/Monolog/Handler/AbstractHandler.php';
require_once __DIR__ . '/Monolog/Handler/AbstractProcessingHandler.php';

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

		joosConfig::init();
		$config		= joosConfig::get2('logging', $name);
		foreach ($config as $handler => $options)
		{
			$this->_logger->pushHandler(joosLoggingHandler::factory($handler, $options)->handler());
		}
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

	public function add($message, $level = joosLoggingLevels::DEBUG)
	{
		$this->_logger->addRecord($level, $message);
	}

	public function debug($message)
	{
		$this->add($message, joosLoggingLevels::DEBUG);
	}

	public function info($message)
	{
		$this->add($message, joosLoggingLevels::INFO);
	}

	public function warning($message)
	{
		$this->add($message, joosLoggingLevels::WARNING);
	}

	public function error($message)
	{
		$this->add($message, joosLoggingLevels::ERROR);
	}

	public function critical($message)
	{
		$this->add($message, joosLoggingLevels::CRITICAL);
	}

	public function alert($message)
	{
		$this->add($message, joosLoggingLevels::ALERT);
	}

}

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
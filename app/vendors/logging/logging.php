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

class joosLoggingTmp {

	protected static $_instances	= array();

	/**
	 * @static
	 * @param string $name
	 * @param string $handler
	 * @param array $params
	 * @param int $level
	 * @return joosLogging
	 */
	public static function instance($name = 'default', $handler = 'stream', $params = array(), $level = joosLoLevelgTypes::DEBUG)
	{
		if ( ! isset(self::$_instances[$name]))
		{
			self::$_instances[$name]	= new joosLogging($name, $handler, $params, $level);
		}

		return self::$_instances[$name];
	}

	/**
	 * @var \Monolog\Logger
	 */
	protected $_logger	= NULL;
	/**
	 * @var \Monolog\Handler\HandlerInterface
	 */
	protected $_handler	= NULL;

	function __construct($name, $handler = 'stream', $params = array(), $log_level = \Monolog\Logger::WARNING)
	{
		$this->_handler	= ucfirst($handler).'Handler';
		require_once __DIR__.'/monolog/src/Monolog/Handler/'.$this->_handler.'.php';

		$handler = new \Monolog\Handler\StreamHandler('php://stderr');

		if ( ! class_exists($this->_handler))
		{
			joosAutoloader::register($this->_handler, __DIR__.'/monolog/src/Monolog/Handler/'.$this->_handler.'.php');
		}

		$this->_handler	= new $this->_handler($params, $params);

		$this->_logger	= new \Monolog\Logger($name);
		$this->_logger->pushHandler($this->_handler);
	}

	/**
	 * @param int $type see joosLoggingTypes
	 * @param int $message
	 * @param array $context
	 * @return bool
	 */
	public function add($type, $message, $context = array())
	{
		return $this->_logger->addRecord($type, $message, $context);
	}

	/**
	 * @param $message
	 * @param array $context
	 * @return bool
	 */
	public function gebug($message, $context = array())
	{
		return $this->add(joosLoLevelgTypes::DEBUG, $message, $context);
	}

	/**
	 * @param $message
	 * @param array $context
	 * @return bool
	 */
	public function info($message, $context = array())
	{
		return $this->add(joosLoLevelgTypes::INFO, $message, $context);
	}

	/**
	 * @param $message
	 * @param array $context
	 * @return bool
	 */
	public function warning($message, $context = array())
	{
		return $this->add(joosLoLevelgTypes::WARNING, $message, $context);
	}

	/**
	 * @param $message
	 * @param array $context
	 * @return bool
	 */
	public function error($message, $context = array())
	{
		return $this->add(joosLoLevelgTypes::ERROR, $message, $context);
	}

	/**
	 * @param $message
	 * @param array $context
	 * @return bool
	 */
	public function critical($message, $context = array())
	{
		return $this->add(joosLoLevelgTypes::CRITICAL, $message, $context);
	}

	/**
	 * @param $message
	 * @param array $context
	 * @return bool
	 */
	public function alert($message, $context = array())
	{
		return $this->add(joosLoLevelgTypes::ALERT, $message, $context);
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
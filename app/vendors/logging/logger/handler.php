<?php

defined('_JOOS_CORE') or die;

class joosLoggingHandler {

	/**
	 * @static
	 * @param $name
	 * @param array $options
	 * @return joosLoggingHandler
	 * @throws Exception
	 */
	public static function factory($name, $options = array())
	{
		$path		= JPATH_BASE .DS .'app' . DS . 'vendors' . DS . 'logging' . DS . 'logger' . DS
					. 'handler'.DS.$name.'.php';
		$class_name	= 'joosLoggingHandler'.ucfirst($name);

		if ( ! file_exists($path))
			throw new Exception('joosLoggingHandler '.$name.' not found');

		require_once $path;

		return new $class_name($options);
	}

	/**
	 * @var \Monolog\Handler\HandlerInterface
	 */
	protected $_handler	= NULL;
	protected $_options	= array();

	public function __construct($options = array())
	{
		$this->_options	= array_merge($this->_options, $options);
	}

	public function handler()
	{
		return $this->_handler;
	}

}
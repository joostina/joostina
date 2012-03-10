<?php

defined('_JOOS_CORE') or die;

class joosLoggingHandlerStream extends joosLoggingHandler {

	public function __construct($options = array())
	{
		$this->_options	= array(
			'level'		=> joosLoggingLevels::DEBUG,
			'stream'	=> JPATH_BASE . DS .'logs' . DS . 'debug-'.date('Y-m-d').'.log'
		);

		parent::__construct($options);

		$this->_handler	= new \Monolog\Handler\StreamHandler($this->_options['stream'], $this->_options['level']);
	}

}
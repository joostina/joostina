<?php

defined('_JOOS_CORE') or die;

require_once JPATH_BASE.'/app/vendors/logging/Monolog/Handler/StreamHandler.php';

class joosLoggingHandlerStream extends joosLoggingHandler {

	public function __construct($options = array())
	{
		$this->_options	= array(
			'level'		=> joosLoggingLevels::DEBUG,
			'stream'	=> JPATH_BASE.'/logs/debug-'.date('Y-m-d').'.log'
		);

		parent::__construct($options);

		$this->_handler	= new \Monolog\Handler\StreamHandler($this->_options['stream'], $this->_options['level']);
	}

}
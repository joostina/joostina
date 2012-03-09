<?php

defined('_JOOS_CORE') or die;

class joosLoggingHandlerFirephp extends joosLoggingHandler {

	public function __construct($options = array())
	{
		$this->_options	= array(
			'level'		=> joosLoggingLevels::INFO,
			'bubble'	=> true
		);

		parent::__construct($options);

		$this->_handler	= new \Monolog\Handler\FirePHPHandler($this->_options['level'], $this->_options['bubble']);
	}

}
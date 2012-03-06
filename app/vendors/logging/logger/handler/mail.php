<?php

defined('_JOOS_CORE') or die;

require_once JPATH_BASE.'/app/vendors/logging/Monolog/Handler/MailHandler.php';
require_once JPATH_BASE.'/app/vendors/logging/Monolog/Handler/NativeMailerHandler.php';

class joosLoggingHandlerMail extends joosLoggingHandler {

	public function __construct($options = array())
	{
		$this->_options	= array(
			'to'		=> 'mail@example.com',
			'subject'	=> 'Message from monolog',
			'from'		=> 'mail@example.com',
			'level'		=> joosLoggingLevels::ERROR
		);

		parent::__construct($options);

		$this->_handler	= new \Monolog\Handler\NativeMailerHandler($this->_options['to'], $this->_options['subject'],
			$this->_options['from'], $this->_options['level']);
	}

}
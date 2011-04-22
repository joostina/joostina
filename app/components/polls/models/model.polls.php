<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Polls - Модель голосований
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Polls
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class Polls extends joosModel {

	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(255)
	 */
	public $title;
	/**
	 * @var mediumtext
	 */
	public $description;
	/**
	 * @var joosText
	 */
	public $questions;
	/**
	 * @var joosText
	 */
	public $variants;
	/**
	 * @var int(11)
	 */
	public $total_users;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__polls', 'id');
	}

}

/**
 * PollsResults - Модель результатов голосований
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Polls
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class PollsResults extends joosModel {

	/**
	 * @var int(11)
	 */
	public $poll_id;
	/**
	 * @var int(11)
	 */
	public $question_id;
	/**
	 * @var int(11)
	 */
	public $variant_id;
	/**
	 * @var int(11)
	 */
	public $result;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__polls_results', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function save_results($poll_id, array $results) {

		foreach ($results as $question_id => $variant_id) {

			$sql = "INSERT INTO `{$this->_tbl}` ( `poll_id`,`question_id`,`variant_id`,`result`)
                    VALUES ( {$poll_id}, $question_id, $variant_id, 1)
                    ON DUPLICATE KEY UPDATE `result`=`result` + 1";
			$this->_db->set_query($sql)->query();
		}

		$polls_users = new PollsUsers;

		$polls_users->poll_id = $poll_id;
		$polls_users->user_id = Users::current()->id;
		$polls_users->user_ip = joosRequest::user_ip();
		$polls_users->created_at = _CURRENT_SERVER_TIME;
		$polls_users->store();

		return true;
	}

}

/**
 * PollsUsers - Модель результатов голосований
 * Модель для работы сайта
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage Polls
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class PollsUsers extends joosModel {

	/**
	 * @var int(11)
	 */
	public $poll_id;
	/**
	 * @var int(11)
	 */
	public $user_id;
	/**
	 * @var varchar(255)
	 */
	public $user_ip;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var joosText
	 */
	public $poll_results;

	/*
	 * Constructor
	 */
	function __construct() {
		$this->joosDBModel('#__polls_users', 'id');
	}

	public function check() {
		$this->filter();
		return true;
	}

	public function already_vote($poll_id) {

		$this->poll_id = $poll_id;
		$this->user_id = Users::current()->id;
		$this->user_ip = joosRequest::user_ip();

		if ($this->find() === true) {
			return true;
		} else {
			return false;
		}
	}

}

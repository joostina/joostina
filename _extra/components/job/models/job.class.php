<?php
/**
 * Job - Компонент вакансий
 * Модель
 *
 * @version 1.0
 * @package Joostina.Components
 * @subpackage Job
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 **/
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//Поддержка метаданных
joosLoader::lib('metainfo', 'seo');

class Job extends joosDBModel {
	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var varchar(255)
	 */
	public $title;
	/**
	 * @var text
	 */
	public $fulltext;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 */
	function __construct(){
		$this->joosDBModel( '#__job', 'id' );
	}
}


/**
 * Class JobResponses
 * @package	Joostina.Components
 * @subpackage	JobResponses
 * @author JoostinaTeam
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @version
 * @created 2011-03-27 12:12:35
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 */
class JobResponses extends joosDBModel {
	/**
	 * @var int(11)
	 */
	public $id;
	/**
	 * @var int(11)
	 */
	public $job_id;
	/**
	 * @var varchar(255)
	 */
	public $username;
	/**
	 * @var varchar(255)
	 */
	public $useremail;
	/**
	 * @var text
	 */
	public $message;
	/**
	 * @var varchar(500)
	 */
	public $resume;

	/*
	 * Constructor
	 */
	function __construct(){
		$this->joosDBModel( '#__job_responses', 'id' );
	}

	public function check() {
		$this->filter();
		return true;
	}


	public function before_insert() {
		return true;
	}


	public function after_insert() {
		return true;
	}


	public function before_store() {
		return true;
	}


	public function after_store() {
		return true;
	}



}

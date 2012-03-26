<?php

class DB {

	static private $_instance;
	static private $_opts = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_EMULATE_PREPARES => true,
		PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8',
	);
	static private $_conn = array(
		'dsn' => 'mysql:host=127.0.0.1;dbname=auto',
		'user' => 'root',
		'passwd' => 'root',
	);

	static public function getInstance() {
		self::$_instance OR self::$_instance = new self;
		return self::$_instance;
	}

	public function test() {
		foreach (self::$_instance->query('SELECT id FROM blog') as $post) {
			echo $post['id'] . "\t";
		}
	}

	private function __construct() {
		try {
			self::$_instance = new PDO(self::$_conn['dsn'],
							self::$_conn['user'],
							self::$_conn['passwd'],
							self::$_opts);
		} catch (PDOException $e) {
			echo 'DB connection error: ' . $e->getMessage();
			exit;
		}
	}

}
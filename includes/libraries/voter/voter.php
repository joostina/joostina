<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

/**
 * Класс управления рейтингами объектов
 * Рейтингования могут быть двух видов:
 * - пользовательские - выставляются непосредственно пользователем и имеют проверку на голосование в течении дня ( check_user_vote_by_day )
 * - системные - запускаются внутри кода и содержит информацию о выполняемом действии и идентификатор объекта над которым это действие выполняется
 */
class Voter {

    /**
     * Текущий объект
     * @var Voter
     */
    private static $_instance;
    private static $_obj_name;
    /**
     * Объект логирования голосования
     * @var Votes
     */
    private static $_votes;
    /**
     * Объект результатов голосования
     * @var VotesResult
     */
    private static $_votesresult;
    public $message;
    public $error;
    public $counter;

    /**
     * Получение объекта голосования
     * @param string $obj_name - название объекта за который ведётся голосование, должно совпадать с %_таблицы
     * @return Voter
     */
    public static function getInstance($obj_name) {

        if (self::$_instance === NULL) {
            self::$_instance = new self;
            self::$_obj_name = $obj_name;
            self::$_votes = new Votes($obj_name);
            self::$_votesresult = new VotesResult($obj_name);
        }

        return self::$_instance;
    }
    
    public static function set_obj_name($obj_name){
    	self::$_obj_name = $obj_name;	
    }

    /**
     * Добавление пользовательского голоса +1 / -1
     * @param stdClass $obj - рейтингуемый объект
     * @param  User $user - объект текущего пользователя
     * @param int $vote_ball - число ( +1/-1 ) добавляемого тейтинга
     */
    public function add_from_user($obj, $user, $vote_ball) {

        $vote = self::$_votes;

        // проверка на голосование за этот объект в течении текущего дня
        if ($vote->check_user_vote_by_ove_vot($user, $obj)) {
            $this->error = 'Голосовать можно лишь один раз, так-то';
            return $this;
        }

        // подключаем библиотеку работы с IP пользователя
        mosMainFrame::addLib('ip');

        $action = 'User';

        // добавляем запись о голосовании в общую таблицу голосов
        $vote = self::$_votes;
        $vote->action_id = sprintf("%u", crc32($action)); // идентификатор действия - сделаем его числово-уникальным через crc32 хеш от выполняемого действия
        $vote->action_name = $action;
        $vote->obj_id = $obj->id;
        $vote->user_id = $user->id;
        $vote->user_ip = IP::get_full_ip();
        $vote->vote = $vote_ball;
        $vote->created_at = _CURRENT_SERVER_TIME;

        // меняем счетчики в результатах голосования
        $result = $vote->store() ? self::$_votesresult->add($obj->id, $vote_ball) : false;

        $this->message = ( $result ) ? 'Голос принят' : 'Всё плохо';
        $this->counter = $result;

        return $this;
    }

    /**
     * Добавление системного рейтинга - увеличение или уменьшение
     * @param stdClass $obj - рейтингуемый объект
     * @param int $action - название действия
     * @param int $action_obj_id - идентификатор объекта над которым совершается действие
     * @param int $vote_ball - сумма добавляемого/удаляемого рейтинга
     * @param int $user_id - необязательный идентификатор пользователя
     */
    public function add_on_action($obj, $action, $action_obj_id, $vote_ball, $user_id = 0) {

        $vote = self::$_votes;

        // добавляем запись о голосовании в общую таблицу голосов
        $vote = self::$_votes;
        $vote->obj_id = $obj->id;
        $vote->action_id = crc32($action);
        $vote->action_name = $action;
        $vote->user_id = $user_id;
        $vote->vote = $vote_ball;
        $vote->created_at = _CURRENT_SERVER_TIME;
        $vote->store();

        // меняем счетчики в результатах голосования
        self::$_votesresult->add($obj->id, $vote_ball);
    }

    public function get_count($obj) {
        $c = database::getInstance()->setQuery('SELECT votes_count FROM #__votes_' . self::$_obj_name . '_results WHERE obj_id=' . (int) $obj->id)->loadResult();     
        return $c ? $c : 0;
    }

    public function get_count_by_id($id) {
        $c = database::getInstance()->setQuery('SELECT votes_count FROM #__votes_' . self::$_obj_name . '_results WHERE obj_id=' . (int) $id)->loadResult();
        return $c ? $c : 0;
    }

    /**
     * Число голосов отданных за объект
     * @param stdClass $obj - объект рейтингования
     * @return integer число голосов
     */
    public static function get_count_voters($obj) {
        $c = database::getInstance()->setQuery('SELECT count(obj_id) FROM #__votes_' . self::$_obj_name . ' WHERE obj_id=' . (int) $obj->id)->loadResult();
        return $c ? $c : 0;
    }

        /**
     * Получение отношения суммы отданных голосов к числу голосовавших отданных за объект
     * @param stdClass $obj - объект рейтингования
     * @return float число отношения
     */
    public static function get_votes_average($obj) {
        $c = database::getInstance()->setQuery('SELECT votes_average FROM #__votes_' . self::$_obj_name . '_results WHERE obj_id=' . (int) $obj->id)->loadResult();
        return $c ? $c : 0;
    }

    /**
     * Получение числа балов выставленных пользователм объекты
     * @param User $user - пользователь
     * @param stdClass $obj - объект рейтингования
     * @return integer - ичлсло баллов
     */
    public static function get_count_vote_from_user_to_obj(User $user, $obj) {
        $c = database::getInstance()->setQuery('SELECT vote FROM #__votes_' . self::$_obj_name . ' WHERE user_id=' . $user->id . ' obj_id=' . (int) $obj->id)->loadResult();
        return $c ? $c : 0;
    }

}

/**
 * Модель голосования за объект
 */
class Votes extends mosDBTable {

    //public $id;
    public $obj_id;
    public $action_id;
    // CRC32 хеш от $action_name
    public $action_name;
    public $action_obj_id;
    public $user_id;
    public $user_ip;
    public $vote;
    public $created_at;
    private $obj_name;

    function __construct($obj_name) {
        $this->mosDBTable('#__votes_' . $obj_name, null); // FALSE - что бы не пытался обновлять несуществующие записи
        $this->obj_name = $obj_name;
    }

    public function after_store() {
        UserExtra::update_votes_cache(User::current(), $this->obj_name);
    }

    /**
     * Проверка, не голосовал ли пользователь за этот объект в течении суток
     * @param User $user - объект проверяемого пользователя
     * @param int $obj_id - идентификатор проверяемого объекта
     * @return boolean - результат проверки
     */
    public function check_user_vote_by_day(User $user, $obj) {
        $cur_time = _CURRENT_SERVER_TIME;
        $sql = "SELECT obj_id from {$this->_tbl} WHERE user_id={$user->id} AND obj_id={$obj->id} AND DATE(created_at)=DATE('{$cur_time}')";
        return (bool) $this->_db->setQuery($sql, 0, 1)->loadResult() > 0;
    }

    /**
     * Проверка, не голосовал ли пользователь за этот объект уже раз
     * @param User $user - объект проверяемого пользователя
     * @param int $obj_id - идентификатор проверяемого объекта
     * @return boolean - результат проверки
     */
    public function check_user_vote_by_ove_vot(User $user, $obj) {
        $sql = "SELECT obj_id from {$this->_tbl} WHERE user_id={$user->id} AND obj_id={$obj->id}";
        return (bool) $this->_db->setQuery($sql, 0, 1)->loadResult() > 0;
    }

}

/**
 * Модель результатов голосования за объект
 */
class VotesResult extends mosDBTable {

    //public $id;
    public $obj_id;
    // сумма голосов
    public $votes_count;
    // число проголосовавших
    public $voters_count;
    // среднее число голосов за объект
    public $votes_average;
    public $created_at;

    function __construct($obj_name) {
        $this->mosDBTable('#__votes_' . $obj_name . '_results', 'id');
    }

    /**
     * Добавление результатов голосования в суммарную таблицу
     * @param int $obj_id идентификатор объекта голосования
     * @param int $vote число голосов за объект
     */
    public function add($obj_id, $vote) {

        // для нулевых результатов не будем делать никаких телодвижений
        if ($vote == 0) {
            return;
        }

        $day = date('Y-m-d', time());

        $sql = "INSERT INTO `{$this->_tbl}` ( `obj_id`,`votes_count`,`voters_count`,`votes_average`,`created_at` )
                    VALUES ( {$obj_id},$vote,1,$vote,'$day' )
                    ON DUPLICATE KEY UPDATE `votes_count`=`votes_count`+($vote), `voters_count`=`voters_count`+1, `votes_average` = `votes_count`/ `voters_count` ";
        return $this->_db->setQuery($sql)->query() ? $this->load_counter($obj_id) : false;
    }

    private function load_counter($obj_id) {
        $sql = "SELECT votes_count FROM `{$this->_tbl}` WHERE `obj_id`= {$obj_id}";
        return $this->_db->setQuery($sql)->loadResult();
    }

}

/*
--
-- Структура таблицы `jos_votes_comment`
--

CREATE TABLE IF NOT EXISTS `#__votes_comment` (
  `obj_id` int(11) unsigned NOT NULL,
  `action_id` int(11) NOT NULL,
  `action_name` varchar(50) NOT NULL,
  `action_obj_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `user_ip` varchar(40) NOT NULL,
  `vote` tinyint(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `obj_id` (`obj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `jos_votes_comment_results`
--

CREATE TABLE IF NOT EXISTS `#__votes_comment_results` (
  `obj_id` int(11) unsigned NOT NULL,
  `votes_count` int(11) NOT NULL,
  `voters_count` int(11) NOT NULL,
  `votes_average` float NOT NULL,
  `created_at` date NOT NULL,
  PRIMARY KEY (`obj_id`),
  KEY `votes_average` (`votes_average`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 *  */
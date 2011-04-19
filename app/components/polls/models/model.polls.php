<?php

/**

 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Class Polls
 * @package    Polls
 * @subpackage    Joostina CMS
 * @created    2011-02-11 13:58:47
 */
class Polls extends joosDBModel
{

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
    function __construct()
    {
        $this->joosDBModel('#__polls', 'id');
    }

    public function check()
    {
        $this->filter();
        return true;
    }

    public function before_insert()
    {
        return true;
    }

    public function after_insert()
    {
        return true;
    }

    public function before_update()
    {
        return true;
    }

    public function after_update()
    {
        return true;
    }

    public function before_store()
    {
        return true;
    }

    public function after_store()
    {
        return true;
    }

    public function before_delete()
    {
        return true;
    }

    public function get_fieldinfo()
    {
        return array(
            'id' => array(
                'name' => 'id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'title' => array(
                'name' => 'title',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'description' => array(
                'name' => 'description',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'questions' => array(
                'name' => 'questions',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'variants' => array(
                'name' => 'variants',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'total_users' => array(
                'name' => 'total_users',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'state' => array(
                'name' => 'state',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
        );
    }

    public function get_tableinfo()
    {
        return array(
            'header_list' => 'Polls',
            'header_new' => 'Создание Polls',
            'header_edit' => 'Редактирование Polls'
        );
    }

    public function get_extrainfo()
    {
        return array(
            'search' => array(),
            'filter' => array(),
            'extrafilter' => array()
        );
    }

}

/**
 * Class PollsResults
 * @package    PollsResults
 * @subpackage    Joostina CMS
 * @created    2011-02-11 13:58:47
 */
class PollsResults extends joosDBModel
{

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
    function __construct()
    {
        $this->joosDBModel('#__polls_results', 'id');
    }

    public function check()
    {
        $this->filter();
        return true;
    }

    public function before_insert()
    {
        return true;
    }

    public function after_insert()
    {
        return true;
    }

    public function before_update()
    {
        return true;
    }

    public function after_update()
    {
        return true;
    }

    public function before_store()
    {
        return true;
    }

    public function after_store()
    {
        return true;
    }

    public function before_delete()
    {
        return true;
    }

    public function get_fieldinfo()
    {
        return array(
            'poll_id' => array(
                'name' => 'poll_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'question_id' => array(
                'name' => 'question_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'variant_id' => array(
                'name' => 'variant_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'result' => array(
                'name' => 'result',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
        );
    }

    public function get_tableinfo()
    {
        return array(
            'header_list' => 'PollsResults',
            'header_new' => 'Создание PollsResults',
            'header_edit' => 'Редактирование PollsResults'
        );
    }

    public function get_extrainfo()
    {
        return array(
            'search' => array(),
            'filter' => array(),
            'extrafilter' => array()
        );
    }

    public function save_results($poll_id, array $results)
    {

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
 * Class PollsUsers
 * @package    PollsUsers
 * @subpackage    Joostina CMS
 * @created    2011-02-11 13:58:47
 */
class PollsUsers extends joosDBModel
{

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
    function __construct()
    {
        $this->joosDBModel('#__polls_users', 'id');
    }

    public function check()
    {
        $this->filter();
        return true;
    }

    public function before_insert()
    {
        return true;
    }

    public function after_insert()
    {
        return true;
    }

    public function before_update()
    {
        return true;
    }

    public function after_update()
    {
        return true;
    }

    public function before_store()
    {
        return true;
    }

    public function after_store()
    {
        return true;
    }

    public function before_delete()
    {
        return true;
    }

    public function get_fieldinfo()
    {
        return array(
            'poll_id' => array(
                'name' => 'poll_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'user_id' => array(
                'name' => 'user_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'user_ip' => array(
                'name' => 'user_ip',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'created_at' => array(
                'name' => 'created_at',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'poll_results' => array(
                'name' => 'poll_results',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
        );
    }

    public function get_tableinfo()
    {
        return array(
            'header_list' => 'PollsUsers',
            'header_new' => 'Создание PollsUsers',
            'header_edit' => 'Редактирование PollsUsers'
        );
    }

    public function get_extrainfo()
    {
        return array(
            'search' => array(),
            'filter' => array(),
            'extrafilter' => array()
        );
    }

    public function already_vote($poll_id)
    {

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

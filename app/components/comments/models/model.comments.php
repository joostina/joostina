<?php defined('_JOOS_CORE') or exit();

/**
 * Модель сайта компонента Comments
 *
 * @package Components\Comments
 * @subpackage Models\Site
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @created 2012-05-04 15:44:22
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 */
class modelComments extends joosModel {
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $id;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $parent_id;
    /**
     * @field varchar(255)
     * @type string
     */
    public $path;
    /**
     * @field tinyint(1)
     * @type int
     */
    public $level;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $obj_id;
    /**
     * @field varchar(30)
     * @type string
     */
    public $obj_option;
    /**
     * @field bigint(21)
     * @type int
     */
    public $obj_option_hash;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $user_id;
    /**
     * @field varchar(50)
     * @type string
     */
    public $user_ip;
    /**
     * @field mediumtext
     * @type string
     */
    public $comment_text;
    /**
     * @field tinyint(1)
     * @type int
     */
    public $state;
    /**
     * @field datetime
     * @type datetime
     */
    public $created_at;

    /*
         * Constructor
         *
         */
    function __construct(){
        parent::__construct( '#__comments', 'id' );
    }

    public function check() {
        $this->filter();
        return true;
    }

    public function before_store() {

        $comment_text = $this->comment_text;
        $comment_text = joosText::text_clean($comment_text);
        $comment_text = joosText::word_limiter($comment_text,200);
        $this->comment_text = $comment_text;


        $this->user_id = joosCore::user()->id;
        $this->user_ip = joosRequest::user_ip();

        // высчитываем родителя и заполняем дерево
        if ($this->parent_id > 0) {

            $parent = new modelComments();
            $parent->load($this->parent_id);

            $this->level = $parent->level + 1;
            $this->path = $parent->path . ',' . $parent->id;

        } else {

            $this->path = 0;
        }
        
        $this->state = 1;
        
        return true;
    }

    public static  function get_comments( $obj_option, $obj_id ) {
        
        $comment = new self;
        return $comment->get_list_cache(array(
            'select' => '*',
            'where' => sprintf('state=1 AND obj_option = \'%s\' AND obj_id = %s', $obj_option, $obj_id),
            'order' => 'parent_id, id ASC',
        ));
    }
    
}

/**
 * Модель сайта компонента CommentsCounter
 *
 * @package Components\CommentsCounter
 * @subpackage Models\Site
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @created 2012-05-04 15:44:22
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 */
class modelCommentsCounter extends joosModel {
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $obj_id;
    /**
     * @field varchar(30)
     * @type string
     */
    public $obj_option;
    /**
     * @field bigint(21)
     * @type int
     */
    public $obj_option_hash;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $last_user_id;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $last_comment_id;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $counter;

    /*
         * Constructor
         *
         */
    function __construct(){
        parent::__construct( '#__comments_counter', 'id' );
    }

    public function check() {
        $this->filter();
        return true;
    }

}
<?php defined('_JOOS_CORE') or exit();

/**
 * Модель сайта компонента ведения блогов, записи блогов
 *
 * @version    1.0
 * @package    Components\Blogs
 * @subpackage Models\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelBlogs extends joosModel
{
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $id;
    /**
     * @field varchar(255)
     * @type string
     */
    public $title;
    /**
     * @field varchar(255)
     * @type string
     */
    public $slug;
    /**
     * @field text
     * @type string
     */
    public $text_intro;
    /**
     * @field longtext
     * @type string
     */
    public $text_full;
    /**
     * @field text
     * @type string
     */
    public $params;
    /**
     * @field tinyint(2) unsigned
     * @type int
     */
    public $category_id;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $user_id;
    /**
     * @field tinyint(1) unsigned
     * @type int
     */
    public $state;
    /**
     * @field datetime
     * @type datetime
     */
    public $created_at;
    /**
     * @field datetime
     * @type datetime
     */
    public $modified_at;

    /*
         * Constructor
         *
         */
    public function __construct()
    {
        parent::__construct( '#__blogs', 'id' );
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

}

/**
 * Модель сайта компонента ведения блогов, категории блогов
 *
 * @package Components\BlogCategory
 * @subpackage Models\Site
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @created 2012-04-22 18:26:20
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 */
class modelBlogsCategory extends joosModel
{
    /**
     * @field int(10) unsigned
     * @type int
     */
    public $id;
    /**
     * @field varchar(200)
     * @type string
     */
    public $title;
    /**
     * @field varchar(100)
     * @type string
     */
    public $slug;
    /**
     * @field text
     * @type string
     */
    public $description;
    /**
     * @field text
     * @type string
     */
    public $params;
    /**
     * @field tinyint(1) unsigned
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
    public function __construct()
    {
        parent::__construct( '#__blogs_category', 'id' );
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
        // формирование ссылки на категорию блогов
        $new_slug = joosRequest::param('slug',false);
        $this->slug = $new_slug ? $new_slug : joosText::text_to_url($this->title);

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

}

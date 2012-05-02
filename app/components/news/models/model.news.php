<?php defined('_JOOS_CORE') or exit();

/**
 * Модель сайта компонента управления новостями
 *
 * @version    1.0
 * @package    Components\News
 * @subpackage Models\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelNews extends joosModel {
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $id;
    /**
     * @field varchar(200)
     * @type string
     */
    public $title;
    /**
     * @field varchar(200)
     * @type string
     */
    public $slug;
    /**
     * @field text
     * @type string
     */
    public $introtext;
    /**
     * @field longtext
     * @type string
     */
    public $fulltext;
    /**
     * @field varchar(255)
     * @type string
     */
    public $image;
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $category_id;
    /**
     * @field datetime
     * @type datetime
     */
    public $created_at;
    /**
     * @field tinyint(1) unsigned
     * @type int
     */
    public $state;
    /*
     * Constructor
     */
    function __construct(){
        parent::__construct( '#__news', 'id' );
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
    public function before_update() {
        return true;
    }
    public function after_update() {
        return true;
    }
    public function before_store() {

        // формирование ссылки на категорию блогов
        $new_slug = joosRequest::param('slug',false);
        $this->slug = $new_slug ? $new_slug : joosText::text_to_url($this->title);
        
        return true;
    }
    public function after_store() {
        return true;
    }
    public function before_delete() {
        return true;
    }
}


/**
 * Модель панели управления компонента управления типами новостей
 *
 * @version    1.0
 * @package    Components\News
 * @subpackage Models\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelNewsType extends joosModel{

}
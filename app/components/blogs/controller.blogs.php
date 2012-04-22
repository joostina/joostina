<?php defined('_JOOS_CORE') or die();

/**
 * Компонент ведения блогов
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Components\Blogs
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsBlogs extends joosController {

    public static function action_before() {

        joosBreadcrumbs::instance()
            ->add('Главная', joosRoute::href('default'))
            ->add('Блоги', joosRoute::href('blog'));
        
    }

    /**
     * Главная страница компонента
     *
     * @static
     * @return array
     */
    public static function index() {
        
        $blogs = new modelBlogs;

        $page = isset(self::$param['page']) ? self::$param['page'] : 0;
        $pager = new joosPager(joosRoute::href('blog'), $blogs->count('WHERE state = 1'), 5);
      	$pager->paginate($page);

        $blog_items = $blogs->get_list(array(
            'select'=>'b.*, bc.title as category_title, bc.slug as category_slug, u.id AS user_id, u.user_name',
            'join'=>' AS b'
                .' INNER JOIN #__blogs_category AS bc ON( b.category_id = bc.id )'
                .' INNER JOIN #__users AS u ON( b.user_id = u.id )',
            'where' => 'b.state = 1',
            'order' => 'b.id DESC',
            'limit' => $pager->limit,
            'offset' => $pager->offset
        ));
        
        joosDocument::instance()
            ->set_page_title('Блоги')
            ->add_meta_tag('description', 'Блоги');
        
        return array(
            'blogs_items' => $blog_items, 
            'pager' => $pager
        );
    }

    public static function view() {

        $id = self::$param['id'];

        $blog_item = new modelBlogs();
        ($blog_item->load( $id ) && $blog_item->state==1)  ? null : joosPages::page404();
        
        $blog_category = new modelBlogsCategory;
        ($blog_category->load( $blog_item->category_id ) && $blog_category->state==1)  ? null : joosPages::page404();

        $author = new modelUsers;
        ($author->load( $blog_item->user_id ) && $author->state==1)  ? null : joosPages::page404();


        joosDocument::instance()
            ->set_page_title($blog_item->title)
            ->add_meta_tag('description', 'Блоги');

        joosBreadcrumbs::instance()
            ->add($blog_item->title);

        return array(
            'blog_item' => $blog_item,
            'blog_category'=>$blog_category,
            'author'=>$author,
        );
    }

    //редактирование
    public static function edit() {

        /**
         *
         * Тут код выполнения задачи
         *
         */


        joosDocument::instance()
            ->set_page_title('Блоги')
            ->add_meta_tag('description', 'Блоги');

        joosBreadcrumbs::instance()
            ->add('Блоги');

        return array(
            
        );

    }

    private static function save(){

        joosCSRF::check_code(1);

        /**
         *
         * Тут код выполнения задачи
         *
         */

        joosDocument::instance()
            ->set_page_title('Блоги')
            ->add_meta_tag('description', 'Блоги');

        joosBreadcrumbs::instance()
            ->add('Блоги');

        return array(
            
        );
    }

}
<?php defined('_JOOS_CORE') or exit();

/**
 * Компонент новостей
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Components\News
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsNews extends joosController {

    public static function action_before() {

        joosBreadcrumbs::instance()
            ->add('Главная', joosRoute::href('default'))
            ->add('Новости', joosRoute::href('news'));

        joosDocument::instance()
            ->add_js_file( JPATH_SITE . '/app/components/news/media/js/news.js' );
        
    }

    /**
     * Главная страница компонента
     *
     * @static
     * @return array
     */
    public static function index() {

        $news = new modelNews();

        $page = isset(self::$param['page']) ? self::$param['page'] : 0;
        $pager = new joosPager(joosRoute::href('news'), $news->count('WHERE state = 1'), 5);
      	$pager->paginate($page);

        $news = $news->get_list(array(
            'where' => 'state = 1',
            'order' => 'id DESC',
            'limit' => $pager->limit,
            'offset' => $pager->offset
        ));

        joosDocument::instance()
            ->set_page_title('Новости')
            ->add_meta_tag('description', 'Новости компании');

        joosBreadcrumbs::instance()
            ->add('Новости');

        return array('news' => $news, 'pager' => $pager);
    }

    public static function view() {

        $id = self::$param['id'];

        $item = new modelNews();
        $item->id = $id;
        $item->find() ? null : joosPages::page404();

        joosDocument::instance()
            ->set_page_title($item->title)
            ->add_meta_tag('description', 'Новости компании');


        return array('item' => $item);
    }

    //редактирование
    public static function edit() {

        /**
         *
         * Тут код выполнения задачи
         *
         */


        joosDocument::instance()
            ->set_page_title('Новости')
            ->add_meta_tag('description', 'Новости сайта');

        joosBreadcrumbs::instance()
            ->add('Новости');

        return array();

    }

    private static function save(){

        joosCSRF::check_code(1);

        /**
         *
         * Тут код выполнения задачи
         *
         */

        joosDocument::instance()
            ->set_page_title('Новости')
            ->add_meta_tag('description', 'Новости сайта');

        joosBreadcrumbs::instance()
            ->add('Новости');

        return array();
    }

}
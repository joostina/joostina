<?php defined('_JOOS_CORE') or die();

/**
 * Компонент новостей
 * Контроллер сайта
 *
 * @version    1.0
 * @package    News
 * @subpackage Controllers
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsNews extends joosController {

    public static function action_before() {

        joosBreadcrumbs::instance()
            ->add('Новости');
    }

    /**
     * Главная страница компонента
     *
     * @static
     * @return array
     */
    public static function index() {

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

    public static function view() {


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
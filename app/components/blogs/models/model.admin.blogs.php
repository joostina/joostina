<?php defined('_JOOS_CORE') or exit();

/**
 * Модель панели управления компонента ведения блогов, управление записыми блогов
 *
 * @version    1.0
 * @package    Components\Blogs
 * @subpackage Models\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelAdminBlogs extends modelBlogs {

    public function get_fieldinfo() {
        return array(
            'title' => array(
                'name' => 'Заголовок',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'editlink',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'text_intro' => array(
                'name' => 'Текст вступления',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'textarea',
                'html_edit_element_param' => array(),
            ),
            'text_full' => array(
                'name' => 'Полный текст',
                'editable' => true,
                'html_edit_element' => 'wysiwyg',
                'html_edit_element_param' => array('editor'=>'redactor'),
            ),
            'category_id' => array(
                'name' => 'Категория',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'option',
                'html_edit_element_param' => array(
                    'call_from' => 'modelAdminBlogs::get_categories_selector'
                ),
            ),
            'user_id' => array(
                'name' => 'Автор',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'value',
                'html_edit_element_param' => array(),
            ),
            'state' => array(
                'name' => 'Состояние',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'status_change',
                'html_table_element_param' => array(
                    'width'=>'25px'
                ),
                'html_edit_element' => 'checkbox',
                'html_edit_element_param' => array(),
            ),
            'created_at' => array(
                'name' => 'Дата создания',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'date_format',
                'html_table_element_param' => array(
                    'date_format'=>'l dS F Y',
                    'width'=>'200'
                ),
                'html_edit_element' => 'value',
                'html_edit_element_param' => array(),
                'hide_on'=>'create'
            ),
            'modified_at' => array(
                'name' => 'Последнее редактирование',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'value',
                'html_edit_element_param' => array(),
                'hide_on'=>'create'
            ),
        );
    }


    public function get_tableinfo() {
        return array(
            'header_main' => 'Блогозаписи',
            'header_list' => 'Записи блогов',
            'header_new' => 'Создание блогозаписи',
            'header_edit' => 'Редактирование блогозаписи'
        );
    }


    public function get_extrainfo() {
        return array(
            'search' => array(),
            'filter' => array(),
            'extrafilter' => array()
        );
    }

    /**
     * Получение списка категорий блоов в виде двумерно массива id=>title
     * 
     * @static
     * @return array
     */
    public static function get_categories_selector(){

        $categories_obj = new modelBlogsCategory;
        return $categories_obj->get_selector();
    }
    
}

/**
 * Модель панели управления компонента вдения блогов, управление категориями блогов
 *
 * @package Components\BlogCategory
 * @subpackage Models\Admin
 * @author JoostinaTeam
 * @copyright (C) 2007-2012 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * @created 2012-04-22 18:26:20
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 */
class modelAdminBlogsCategory extends modelBlogsCategory {

    public function get_fieldinfo() {
        return array(
            'title' => array(
                'name' => 'Название категории',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'editlink',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'slug' => array(
                'name' => 'Текст ссылки',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'description' => array(
                'name' => 'Описание',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'textarea',
                'html_edit_element_param' => array(),
            ),
            'state' => array(
                'name' => 'Состояние',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'status_change',
                'html_table_element_param' => array(),
                'html_edit_element' => 'checkbox',
                'html_edit_element_param' => array(),
            ),
        );
    }


    public function get_tableinfo() {
        return array(
            'header_main' => 'Категории блогов',
            'header_list' => 'BlogCategory',
            'header_new' => 'Создание BlogCategory',
            'header_edit' => 'Редактирование BlogCategory'
        );
    }


    public function get_extrainfo() {
        return array(
            'search' => array(),
            'filter' => array(),
            'extrafilter' => array()
        );
    }

}
<?php defined('_JOOS_CORE') or die();

/**
 * Модель панели управления компонента управления новостями
 *
 * @version    1.0
 * @package    Components\News
 * @subpackage Models\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelAdminNews extends modelNews {
    public function get_fieldinfo() {
        return array(
            'id' => array(
                'name' => 'ID',
                'editable' => false,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'title' => array(
                'name' => 'Заголовок',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'editlink',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array('class' => 'span8'),
            ),
            'slug' => array(
                'name' => 'Ссылка',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array('class' => 'span6'),
            ),
            'introtext' => array(
                'name' => 'Вводный текст',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'textarea',
                'html_edit_element_param' => array(),
            ),
            'fulltext' => array(
                'name' => 'Полный текст',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'wysiwyg',
                'html_edit_element_param' => array('editor'=>'redactor'),
            ),
            'image' => array(
                'name' => 'Картинка новости',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'category_id' => array(
                'name' => 'category_id',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'value',
                'html_table_element_param' => array(),
                'html_edit_element' => 'edit',
                'html_edit_element_param' => array(),
            ),
            'created_at' => array(
                'name' => 'Дата создания',
                'editable' => true,
                'in_admintable' => true,
                'html_table_element' => 'date_format',
                'html_table_element_param' => array(
                    'date_format' => 'd F в H:m',
                ),
                'html_edit_element' => 'value',
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
            'header_main' => 'Новости',
   			'header_list' => 'Все новости',
   			'header_new' => 'Создание новости',
   			'header_edit' => 'Редактирование новости'
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

/**
 * Модель панели управления компонента управления типами новостей
 *
 * @version    1.0
 * @package    Components\News
 * @subpackage Models\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelAdminNewsType extends modelNewsType {

}
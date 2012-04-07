<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * modelAdminPages - Модель компонента независимыми страницами
 * Модель панели управления
 *
 * @version    1.0
 * @package    Models
 * @subpackage Pages
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelAdminPages extends modelPages {

	public function get_fieldinfo() {
		return array('id' => array(
				'name' => 'ID',
				'editable' => false,
				'in_admintable' => false,
				'html_table_element' => 'value',
				'html_table_element_param' => array(
					'width' => '20px',
					'align' => 'center'
				)
			),
			'title' => array('name' => 'Заголовок',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_edit_element' => 'edit',
				'html_table_element' => 'editlink',
			),
			'state' => array('name' => 'Состояние',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'checkbox',
				'html_table_element' => 'state_box',
				'html_edit_element_param' => array(
					'text' => 'Опубликовано',
				),
				'html_table_element' => 'status_change',
				'html_table_element_param' => array(
					'align' => 'center',
					'class' => 'td-state-joiadmin',
					'width' => '80px',
				)
			),
			'slug' => array('name' => 'Ссылка',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'modelAdminPages::get_slug',
				),
			),
			'text' => array('name' => 'Описание',
				'editable' => true,
				'html_edit_element' => 'wysiwyg',
				'html_edit_element_param' => array(),
			),
            'created_at' => array(
                'name' => 'Создано',
                'editable' => true,
                'in_admintable' => true,
                'html_edit_element' => 'value',
                'html_table_element' => 'date_format',
                'html_table_element_param' => array(
                    'date_format' => 'd F в H:m',
                    'width' => '200px',
                    'align' => 'center'
                )
            ),
		);
	}

	public function get_tableinfo() {
		return array('header_main' => 'Страницы',
			'header_list' => 'Все страницы',
			'header_new' => 'Создание страницы',
			'header_edit' => 'Редактирование страницы');
	}

	public function get_tabsinfo() {
		return array('first' => array(
				'title' => 'Основное',
				'fields' => array('title', 'created_at', 'state', 'slug', 'text')
			),
			'second' => array(
				'title' => 'Метаданные',
				'fields' => array('metainfo')
			)
		);
	}

	public static function get_slug($item) {
		return JPATH_SITE . '/
			<input type="text" style="width: 50%;" class="text_area" size="30" value="' . $item->slug . '" name="slug" id="slug">
			<span class="g-pseudolink" id="pages_slug_generator" obj_id="' . $item->id . '">Сформировать</span>
		';
	}

}
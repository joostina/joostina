<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * adminNews - Модель компонента управления новостями
 * Модель панели управления
 *
 * @version 1.0
 * @package Joostina.Models
 * @subpackage News
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class adminNews extends News {

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
				'editable' => false,
				'in_admintable' => false,
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
				'html_edit_element_param' => array(),
			),
			'created_at' => array(
				'name' => 'Создано',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'state' => array(
				'name' => 'Опубликовано',
				'editable' => true,
				'sortable' => true,
				'in_admintable' => true,
				'editlink' => true,
				'html_edit_element' => 'checkbox',
				'html_table_element' => 'state_box',
				'html_edit_element_param' => array(
					'text' => 'Опубликовано',
				),
				'html_table_element' => 'statuschanger',
				'html_table_element_param' => array(
					'align' => 'center',
					'class' => 'td-state-joiadmin',
					'width' => '20px',
				),
			),
			'slug' => array(
				'name' => 'slug',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
			'introtext' => array(
				'name' => 'Анонс (без HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'textarea',
				'html_edit_element_param' => array(),
			),
			'fulltext' => array(
				'name' => 'Полный текст (с HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'wysiwyg',
				'html_edit_element_param' => array(),
			),
			'news_pictures_uploader' => array(
				'name' => 'Картинко',
				'editable' => true,
				'in_admintable' => false,
				'html_table_element' => 'value',
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'adminNews::get_picture_uploader',
				),
			),
			//подключение функционала заполнения мета-информации
			'metainfo' => array(
				'name' => 'params',
				'editable' => true,
				'html_edit_element' => 'json',
				'html_edit_element_param' => array(
					'call_from' => 'joosMetainfo::get_scheme'
				),
			),
			//подключение функционала парметров
			'params' => array(
				'name' => 'Параметры',
				'editable' => true,
				'html_edit_element' => 'params',
				'html_edit_element_param' => array(
					'call_from' => 'joosParams::get_scheme'
				),
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

	public function get_tabsinfo() {
		return array(
			'first' => array(
				'title' => 'Основное',
				'fields' => array(
					'title', 'created_at', 'state', 'slug',
					'introtext', 'fulltext', 'news_pictures_uploader'
				)
			),
			'second' => array('title'=>'Метаданные', 'fields' => array('metainfo'))
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array(
				'title', 'slug'
			),
//			'filter' => array(
//				'type_id' => array(
//					'name' => 'Категория',
//					'call_from' => 'News::get_types'
//				),
//			)
		);
	}

	public function check() {

		$jevix = new JJevix();
		$this->fulltext = $jevix->Parser($this->fulltext);
		$this->introtext = $jevix->Parser($this->introtext);

		$this->filter(array('fulltext'));

		// TODO тут можно сделать формирование ссылочного слага из заголовка новости, либо добавить отдельное поля во вьюшку
		$this->slug = _CURRENT_SERVER_TIME;
		return true;
	}

	public function before_insert(){
		$this->created_at = _CURRENT_SERVER_TIME;
	}

	public function after_insert() {
		$this->created_at = _CURRENT_SERVER_TIME;
		return true;
	}

	public function after_update() {
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_store() {
		$main_image_id = joosRequest::request('main_image_id', 0);
		$main_image_path = joosRequest::request('image', '');

		if ($main_image_id) {
			$attach = json_decode($this->attachments, true);
			$attach['main'] = array('id' => $main_image_id, 'path' => $main_image_path);
			$this->attachments = json_encode($attach);
		}

		return true;
	}

	public function before_delete() {
		return true;
	}

	public static function get_picture_uploader($item) {

		$attachments = json_decode($item->attachments, true);

		$image_id = 0;
		if ($attachments) {
			$image = $attachments['main'];
			$image_id = $image['id'];
		}

		Joosdocument::instance()->add_js_file(JPATH_SITE . '/media/js/valumsfileuploader/fileuploader.js');
		$js_code = "
			var uploader = new qq.FileUploader({
				element: $('#file-uploader-news')[0],
				multiple: false,
				action: 'ajax.index.php?option=news&task=image_uploader' ,
				button_label: 'Загрузить картинку',
				params: {
					image_id: $image_id
				},
				//debug: true,
				allowedExtensions: ['jpg', 'jpeg', 'png'],
				onComplete: function(id, fileName, responseJSON){
					var dateob = new Date();
					$('#newsimage').attr('src', _live_site + responseJSON.location + 'thumb.jpg' + '?'+dateob.getTime() );
					$('#image').val( responseJSON.location );
					$('#main_image_id').val( responseJSON.file_id );
				}
			});
		";
		Joosdocument::instance()->add_js_code($js_code);

		$image = $item->image ? News::get_image($item, 'thumb', array('id' => 'newsimage')) : News::get_image_default(array('id' => 'newsimage'));

		$image .= '<div id="file-uploader-news"></div>';
		return $image . '
			<br />
			<input type="hidden" id="image" name="image" value="' . $item->image . '" />
			<input type="hidden" id="main_image_id" name="main_image_id" value="' . $image_id . '" />';
	}

}
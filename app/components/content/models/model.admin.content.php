<?php

/**
 * Content - компонент управления контентом
 * Модель
 *
 * @version 1.0
 * @package ComponentsAdmin
 * @subpackage Content
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Class Content
 * @package    Content
 * @subpackage    Joostina CMS
 * @created    2011-02-09 11:51:55
 */
class adminContent extends Content {

	public function check() {
		joosLoader::lib('jevix', 'text');
		$jevix = new JJevix();
		$this->fulltext = $jevix->Parser($this->fulltext);
		$this->introtext = $jevix->Parser($this->introtext);

		$this->filter(array('fulltext'));

		return true;
	}

	public function before_insert() {
		$this->created_at = _CURRENT_SERVER_TIME;
		$this->ordering = $this->max('ordering') + 1;
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

		if (isset($_POST['images'])) {

			$images = $_POST['images'];

			$i = 1;
			$_images = array();
			foreach ($images as $img) {
				if ($img['id'] && $img['path']) {
					if ($i == 1) {
						$this->image = $img['path'];
					}
					$_images['image_' . $i] = $img;
					++$i;
				}
			}
			$this->attachments = json_encode(array('images' => $_images));

			//если какие-то изображения отмечены для использования в качестве изображений для родительской категории -
			//запишем соответствующие данные в данные категории
			$items_images = array();
			foreach ($_images as $key => $img) {
				if (isset($img['for_category'])) {
					$items_images[$img['id']] = $img['path'];
				}
			}

			$category = new CategoriesDetails();
			$category->load($this->category_id);
			$cat_attaches = json_decode($category->attachments, true);

			if ($items_images) {
				foreach ($items_images as $_id => $_path) {
					$cat_attaches['items_images'][$_id] = $_path;
				}
			} else {
				foreach ($_images as $img) {
					if (isset($cat_attaches['items_images'][$img['id']])) {
						unset($cat_attaches['items_images'][$img['id']]);
					}
				}
			}
			$category->attachments = json_encode($cat_attaches);
			$category->store();
		}

		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array(
			'id' => array(
				'name' => 'id',
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
				'html_edit_element_param' => array(),
			),
			'category_id' => array(
				'name' => 'Категория',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'extra',
				'html_table_element_param' => array(
					'call_from' => 'adminContent::get_catname',
				),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'adminContent::get_cats_selector',
				)
			),
			'created_at' => array(
				'name' => 'Создано',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'value',
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
					'statuses' => array(
						0 => 'Скрыто',
						1 => 'Опубликовано'
					),
					'images' => array(
						0 => 'publish_x.png',
						1 => 'publish_g.png',
					),
					'align' => 'center',
					'class' => 'td-state-joiadmin',
					'width' => '20px',
				)
			),
			'ordering' => array(
				'name' => 'Порядок',
				'editable' => false,
				'in_admintable' => true,
				'html_table_element' => 'ordering',
				'html_table_element_param' => array(
					'class' => 'ordering',
					'width' => '100px',
					'align' => 'center'
				),
				'html_edit_element' => 'edit',
				'html_edit_element_param' => array(),
			),
//			'special' => array(
//				'name' => 'Конкурс',
//				'editable' => true,
//				'sortable' => true,
//				'in_admintable' => true,
//				'editlink' => true,
//				'html_edit_element' => 'checkbox',
//				'html_table_element' => 'state_box',
//				'html_edit_element_param' => array(
//					'text' => 'Конкурс',
//				)
//			),
			'slug' => array(
				'name' => 'Ссылка',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'adminContent::get_slug_editor_helper',
				),
			),
			'introtext' => array(
				'name' => 'Анонс (без HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area',
				'html_edit_element_param' => array(),
			),
			'fulltext' => array(
				'name' => 'Полный текст (с HTML)',
				'editable' => true,
				'in_admintable' => true,
				'html_table_element' => 'value',
				'html_table_element_param' => array(),
				'html_edit_element' => 'text_area_wysiwyg',
				'html_edit_element_param' => array(),
			),
			//подключение функционала дополнительных полей
			'extra_fields' => array(
				'name' => 'Дополнительные данные',
				'editable' => true,
				'html_edit_element' => 'extra_fields',
				'html_edit_element_param' => array(
					'call_from' => 'Content::get_extrafields',
					'call_params' => array('group' => 'content', 'subgroup' => 'category')
				),
			),
			'content_pictures_uploader' => array(
				'name' => 'Изображение',
				'editable' => true,
				'in_admintable' => false,
				'html_table_element' => 'value',
				'html_edit_element' => 'extra',
				'html_edit_element_param' => array(
					'call_from' => 'adminContent::get_picture_uploader',
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
			//подключение функционала параметров
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
			'header_main' => 'Статьи',
			'header_list' => 'Все статьи',
			'header_new' => 'Создание статьи',
			'header_edit' => 'Редактирование статьи'
		);
	}

	public function get_extrainfo() {
		return array(
			'search' => array(),
			'filter' => array(
				'category_id' => array(
					'name' => 'Категория',
					'call_from' => 'adminContent::get_categories_filter'
				),
			)
		);
	}

	public static function get_cats_selector($item) {

		$cats = new Categories('content');
		return $cats->get_cats_selector_for_items($item);
	}

	public static function get_categories_filter($item) {
		$cats = new Categories('content');

		$types = array();
		foreach ($cats->get_full_tree_simple() as $catid => $cat) {
			$types[$catid] = str_repeat('-&nbsp;', $cat['level'] * 2) . $cat['name'];
		}


		return $types;
	}

	public static function get_catname($item) {
		return $item->catname;
	}

	public static function get_slug_editor_helper($item) {
		return '
			<input type="text" style="width: 80%;" class="text_area" size="100" value="' . $item->slug . '" name="slug" id="slug">
			<span class="g-pseudolink" id="content_slug_generator" obj_id="' . $item->id . '">Сформировать</span>
		';
	}

	public static function get_picture_uploader($item) {

		Joosdocument::instance()->add_js_file(JPATH_SITE . '/media/js/valumsfileuploader/fileuploader.js');

		$attachments = json_decode($item->attachments, true);

		//Ищем картинки
		$images = array();
		if (isset($attachments['images'])) {
			$images = $attachments['images'];
		} else {
			$images[] = 1;
		}

		$js_code = '';
		$return = '<div id="content_uploader_area">';

		$img_counter = isset($attachments['images']) ? count($attachments['images']) : 0;
		$return .= '<input type="hidden" id="img_counter" name="img_counter" value="' . ($img_counter + 1) . '" />';

		$i = 1;
		foreach ($images as $_tmp) {

			//Изображение
			$image = isset($images['image_' . $i]) ? $images['image_' . $i] : array();

			$return .= self::get_uploader($item, $image, $i);

			//id изображения
			$image_id = $image ? $image['id'] : 0;
			$js_code .= self::get_js_code_for_uploader($image_id, $i);
			$i++;
		}

		$return .= '</div><div class="b b-left b-33" style="padding:30px 0 0 0"><button type="button" id="add_pic">Ещё!</button></div></div>';

		Joosdocument::instance()->add_js_code($js_code);

		return $return;
	}

	public static function get_uploader($item, $image, $i) {

		$return = '';

		//id изображения
		$image_id = $image ? $image['id'] : 0;
		//путь до изображения (директория)
		$image_path = $image ? $image['path'] : '';
		//если это изображение отмечено в качестве изображения для родительской категории
		$image_for_category = ($image && isset($image['for_category'])) ? true : false;

		$return .= '<div class="b b-left b-33" style="padding:30px 0 0 0">';

		//Поля с данными об изображениях
		$return .= '<input type="hidden" id="image_' . $i . '_id" name="images[image_' . $i . '][id]" value="' . $image_id . '" />';
		$return .= '<input type="hidden" id="image_' . $i . '_path" name="images[image_' . $i . '][path]" value="' . $image_path . '" />';

		$id = 'contentimage_' . $i;
		if ($image_id) {
			$item->image_path = $image_path;
			$return .= Content::get_image($item, 'thumb', array('width' => '150', 'id' => $id));
		} else {
			$return .= Content::get_image_default(array('width' => '150', 'id' => $id));
		}

		$return .= '<br/><div id="image_' . $i . '_controls"><div class="content_upload_button_wrap" id="file-uploader-content_' . $i . '"></div>
		<button rel="image_' . $i . '" class="content_delete_image" type="button">X</button>';

		$checked = $image_for_category ? 'checked="checked"' : '';
		$return .= '<input ' . $checked . ' type="checkbox" id="image_' . $i . '_for_category" name="images[image_' . $i . '][for_category]" value="1" />';
		$return .= '&nbsp;<label class="content_img_forcat">для категории</label>';

		$return .= '</div></div>';

		return $return;
	}

	public static function get_js_code_for_uploader($image_id, $counter = 1) {
		$js_code = "
			var uploader_$counter = new qq.FileUploader({
				element: $('#file-uploader-content_$counter')[0],
				multiple: false,
				action:   'ajax.index.php?option=content&task=image_uploader' ,
				button_label: '<img src=\"/media/images/admin/gnome-logout.png\" />',
				params: {
					category_id: $('#category_id').val(),
					image_id: $image_id,
					counter: $counter
				},
				//debug: true,
				allowedExtensions: ['jpg', 'jpeg', 'png'],
				onComplete: function(id, fileName, responseJSON){
					var dateob = new Date();
					$('#contentimage_$counter').attr('src', _live_site + responseJSON.location + 'thumb.jpg' + '?'+dateob.getTime() );
					$('#image_" . $counter . "' + '_path').val( responseJSON.location );
					$('#image_" . $counter . "'+'_id').val( responseJSON.file_id );
				}
			});

		";

		return $js_code;
	}

}
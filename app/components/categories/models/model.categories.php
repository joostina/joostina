<?php
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * modelCategories - Модель категорий
 * Модель панели управления
 *
 * @version    1.0
 * @package    Joostina.Models
 * @subpackage modelCategories
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelCategories extends joosNestedSet {

	/**
	 * @var mediumint(8) unsigned
	 */
	public $id;
	/**
	 * @var varchar(255)
	 */
	public $name;
	/**
	 * @var tinyint(1)
	 */
	public $state;

	/*
	 * Constructor
	 */
	public function __construct( $group = '' ) {
		parent::__construct( array ( 'table' => '#__categories' ) );

		$group       = $group ? $group : joosRequest::request( 'group' , '' );
		$this->group = $group;
	}

	public function check() {
		//$this->filter(array('desc_short', 'desc_full'));
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
		$this->slug = $this->slug ? $this->slug : joosText::str_to_url( $this->name );
		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array ( 'id'                    => array ( 'name'               => 'id' ,
		                                                  'editable'           => false ,
		                                                  'in_admintable'      => true ,
		                                                  'html_table_element' => 'value' ,
		                                                  'html_edit_element'  => 'value' ) ,
		               'parent_id'             => array ( 'name'                    => 'Родительская категория' ,
		                                                  'editable'                => true ,
		                                                  'in_admintable'           => false ,
		                                                  'html_edit_element'       => 'extra' ,
		                                                  'html_edit_element_param' => array ( 'call_from' => 'modelCategories::get_cats_selector' , ) ) ,
		               'name'                  => array ( 'name'                     => 'Название' ,
		                                                  'editable'                 => true ,
		                                                  'in_admintable'            => true ,
		                                                  'html_table_element'       => 'value' ,
		                                                  'html_table_element_param' => array () ,
		                                                  'html_edit_element'        => 'edit' ,
		                                                  'html_edit_element_param'  => array () , ) ,
		               'state'                 => array ( 'name'                     => 'Опубликовано' ,
		                                                  'editable'                 => true ,
		                                                  'sortable'                 => true ,
		                                                  'in_admintable'            => true ,
		                                                  'editlink'                 => true ,
		                                                  'html_edit_element'        => 'checkbox' ,
		                                                  'html_table_element'       => 'state_box' ,
		                                                  'html_edit_element_param'  => array ( 'text' => 'Опубликовано' , ) ,
		                                                  'html_table_element'       => 'status_change' ,
		                                                  'html_table_element_param' => array ( 'align' => 'center' ,
		                                                                                        'class' => 'td-state-joiadmin' ,
		                                                                                        'width' => '20px' , ) ) ,
		               'group'                 => array ( 'name'                     => 'Группа' ,
		                                                  'editable'                 => true ,
		                                                  'in_admintable'            => false ,
		                                                  'html_table_element'       => 'value' ,
		                                                  'html_table_element_param' => array () ,
		                                                  'html_edit_element'        => 'hidden' ,
		                                                  'html_edit_element_param'  => array () , ) ,
		               'slug'                  => array ( 'name'                     => 'Ссылка' ,
		                                                  'editable'                 => true ,
		                                                  'in_admintable'            => true ,
		                                                  'html_table_element'       => 'value' ,
		                                                  'html_table_element_param' => array () ,
		                                                  'html_edit_element'        => 'extra' ,
		                                                  'html_edit_element_param'  => array ( 'call_from' => 'CategoriesDetails::get_slug_editor_helper' , ) , ) ,
		               'desc_short'            => array ( 'name'                     => 'Короткое описание' ,
		                                                  'editable'                 => true ,
		                                                  'in_admintable'            => true ,
		                                                  'html_table_element'       => 'value' ,
		                                                  'html_table_element_param' => array () ,
		                                                  'html_edit_element'        => 'textarea' ,
		                                                  'html_edit_element_param'  => array () , ) ,
		               'desc_full'             => array ( 'name'                     => 'Полное описание' ,
		                                                  'editable'                 => true ,
		                                                  'in_admintable'            => true ,
		                                                  'html_table_element'       => 'value' ,
		                                                  'html_table_element_param' => array () ,
		                                                  'html_edit_element'        => 'wysiwyg' ,
		                                                  'html_edit_element_param'  => array () , ) ,
		               'created_at'            => array ( 'name'                     => 'Создано' ,
		                                                  'editable'                 => true ,
		                                                  'in_admintable'            => true ,
		                                                  'html_table_element'       => 'value' ,
		                                                  'html_table_element_param' => array () ,
		                                                  'html_edit_element'        => 'edit' ,
		                                                  'html_edit_element_param'  => array () , ) ,
		               'cat_pictures_uploader' => array ( 'name'                    => 'Изображение' ,
		                                                  'editable'                => true ,
		                                                  'in_admintable'           => false ,
		                                                  'html_table_element'      => 'value' ,
		                                                  'html_edit_element'       => 'extra' ,
		                                                  'html_edit_element_param' => array ( 'call_from' => 'CategoriesDetails::get_picture_uploader' , ) , ) ,
			//подключение функционала заполнения мета-информации
		               'metainfo'              => array ( 'name'                    => 'params' ,
		                                                  'editable'                => true ,
		                                                  'html_edit_element'       => 'json' ,
		                                                  'html_edit_element_param' => array ( 'call_from' => 'joosMetainfo::get_scheme' ) , ) ,
			//подключение функционала парметров
		               'params'                => array ( 'name'                    => 'Параметры' ,
		                                                  'editable'                => true ,
		                                                  'html_edit_element'       => 'params' ,
		                                                  'html_edit_element_param' => array ( 'call_from' => 'joosParams::get_scheme' ) , ) , );
	}

	public function get_tableinfo() {
		return array ( 'header_list' => 'Категории' ,
		               'header_new'  => 'Создание категории' ,
		               'header_edit' => 'Редактирование категории' );
	}

	public static function get_cats_selector( $obj ) {

		$cats = new self;
		$tree = $cats->get_full_tree_simple();

		array_unshift( $tree , array ( 'id'    => 1 ,
		                               'level' => '0' ,
		                               'name'  => '----' ) );

		ob_start();
		?>
	<select name="parent_id" id="category_id">
		<?php foreach ( $tree as $value ): ?>
		<?php $selected = $value['id'] == $obj->parent_id ? ' selected="selected" ' : ''; ?>
		<option <?php echo $selected ?> value="<?php echo $value['id'] ?>">
			<?php echo str_repeat( '-&nbsp;' , $value['level'] * 2 ) . $value['name'] ?>
		</option>
		<?php endforeach; ?>
	</select>
	<?php
		return ob_get_clean();
	}

	public function get_cats_selector_for_items( $obj ) {

		if ( joosRequest::request( 'parentcat' ) ) {
			$parent = joosRequest::request( 'parentcat' );
			$this->load( $parent );
			$tree = $this->get_branch( $this->lft , $this->rgt );
		} else {
			$tree = $this->get_full_tree_simple();
		}

		ob_start();
		?>
	<select name="category_id" id="category_id">
		<?php foreach ( $tree as $value ): $selected = $value['id'] == $obj->category_id ? ' selected="selected" ' : ''; ?>
		<option <?php echo $selected ?> value="<?php echo $value['id'] ?>">
			<?php echo str_repeat( '-&nbsp;' , $value['level'] * 2 ) . $value['name'] ?>
		</option>
		<?php endforeach; ?>
	</select>
	<?php
		return ob_get_clean();
	}

	public function get_link_suff() {
		return $this->group ? '&amp;group=' . $this->group : '';
	}

	//Получение параметров категории по ID категории
	//Работает следующим образом:
	//	- выбирается путь до категории от корня
	// 	- выбираются все подходящие параметры (одним запросом), включая и дефолтные
	//	- если у требуемой категории есть параметры - отдаются именно они
	//		- иначе: отдаются параметры ближайшей родительской категории. Если же и их нет, то отдаются дефолтные параметры компонента
	public static function get_params_by_category( $id , $group ) {

		$cats = new self;
		$path = $cats->get_path_from_root( $id );
		unset( $path[1] );

		$ids         = array_keys( $path );

		$params      = new joosParams;
		$cats_params = $params->get_list( array ( 'where' => ' (`subgroup` = "category" AND `object` IN (' . implode( ',' , $ids ) . '))  OR  (`group` = "' . $group . '" AND `subgroup` = "default") ' ) );

		$ids         = array_reverse( $ids );
		array_push( $ids , 'default' );

		$params_array = array ();
		foreach ( $cats_params as $p ) {
			$key                = $p->object ? $p->object : 'default';
			$params_array[$key] = $p->data;
		}

		foreach ( $ids as $id ) {
			if ( isset( $params_array[$id] ) ) {
				return $params_array[$id];
			}
		}


		return $cats_params;
	}

	//Получение дополнительных полея для создания/редактирования записей ID категории
	//Работает следующим образом:
	//	- выбирается путь до категории от корня
	// 	- выбираются все подходящие поля (одним запросом), включая и дефолтные
	//	- если у требуемой категории есть поля - отдаются именно они
	//		- иначе: отдаются поля ближайшей родительской категории. Если же и их нет, то возвращается FALSE
	public static function get_extrafields_by_category( $id , $group ) {

		$cats = new self;
		$path = $cats->get_path_from_root( $id );
		unset( $path[1] );

		$ids         = array_keys( $path );

		$ef          = new Extrafields();
		$cats_fields = $ef->get_list( array ( 'where' => '
					(`subgroup` = "category" AND `object` IN (' . implode( ',' , $ids ) . '))
					OR  (`group` = "' . $group . '" AND `subgroup` = "default")' ) );

		$ids         = array_reverse( $ids );

		array_push( $ids , 'default' );

		if ( count( $cats_fields ) < 1 ) {
			return false;
		}

		$fields_array = array ();
		foreach ( $cats_fields as $f ) {
			$key                        = $f->object ? $f->object : 'default';
			$fields_array[$key][$f->id] = $f;
		}

		foreach ( $ids as $id ) {
			if ( isset( $fields_array[$id] ) ) {
				return $fields_array[$id];
			}
		}

		return false;
	}

	public static function set_breadcrumbs( $category , $path = null , $last_as_link = false ) {

		if ( !$path ) {
			joosBreadcrumbs::instance()->add( $category->name );
		} else {
			unset( $path[0] );
			$last = array_pop( $path );
			foreach ( $path as $_cat ) {
				joosBreadcrumbs::instance()->add( $_cat->name , joosRoute::href( 'category_view' , array ( 'slug' => $_cat->slug ) ) );
				joosDocument::instance()->add_title( $_cat->name );
			}
			if ( $last_as_link ) {
				joosBreadcrumbs::instance()->add( $last->name , joosRoute::href( 'category_view' , array ( 'slug' => $last->slug ) ) );
			} else {
				joosBreadcrumbs::instance()->add( $last->name );
			}

			//joosDocument::instance()->add_title($last->name);
		}
	}

}

/**
 * CategoriesDetails - Модель расширенной информации о категориях
 * Модель панели управления
 *
 * @version    1.0
 * @package    Joostina.Models
 * @subpackage modelCategories
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class CategoriesDetails extends joosModel {

	/**
	 * @var int(11)
	 */
	public $cat_id;
	/**
	 * @var tinytext
	 */
	public $desc_short;
	/**
	 * @var joosText
	 */
	public $desc_full;
	/**
	 * @var timestamp
	 */
	public $created_at;
	/**
	 * @var int(11)
	 */
	public $user_id;
	/**
	 * @var varchar(255)
	 */
	public $image;
	/**
	 * @var joosText
	 */
	public $attachments;
	/**
	 * @var varchar(300)
	 */
	public $video;

	/*
	 * Constructor
	 */
	function __construct() {
		parent::__construct( '#__categories_details' , 'cat_id' );
	}

	public function check() {

		$this->filter( array ( 'desc_full' ) );

		$jevix           = new JJevix();
		$this->desc_full = $jevix->Parser( $this->desc_full );

		return true;
	}

	public function before_insert() {
		$this->created_at = JCURRENT_SERVER_TIME;
		$this->user_id    = modelUsers::current()->id;
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

		if ( joosRequest::request( 'option' ) != 'categories' ) {
			return true;
		}

		$attach = json_decode( $this->attachments , true );

		if ( isset( $_POST['images'] ) ) {
			$attach['images'] = array ();

			$images           = $_POST['images'];

			$i                = 1;
			$_images          = array ();
			foreach ( $images as $img ) {
				if ( $img['id'] && $img['path'] ) {
					if ( $i == 1 ) {
						$this->image = $img['path'];
					}
					if ( isset( $img['main_image'] ) && $img['main_image'] == 1 ) {
						$this->image = $img['path'];
					}
					$_images['image_' . $i] = $img;
					++$i;
				}
			}
			$attach['images'] = $_images;
		}

		$this->attachments = json_encode( $attach );

		return true;
	}

	public function after_store() {
		return true;
	}

	public function before_delete() {
		return true;
	}

	public function get_fieldinfo() {
		return array ( 'category_id' => array ( 'name'                     => 'category_id' ,
		                                        'editable'                 => true ,
		                                        'in_admintable'            => true ,
		                                        'html_table_element'       => 'value' ,
		                                        'html_table_element_param' => array () ,
		                                        'html_edit_element'        => 'edit' ,
		                                        'html_edit_element_param'  => array () , ) ,
		               'desc_short'  => array ( 'name'                     => 'desc_short' ,
		                                        'editable'                 => true ,
		                                        'in_admintable'            => true ,
		                                        'html_table_element'       => 'value' ,
		                                        'html_table_element_param' => array () ,
		                                        'html_edit_element'        => 'edit' ,
		                                        'html_edit_element_param'  => array () , ) ,
		               'desc_full'   => array ( 'name'                     => 'desc_full' ,
		                                        'editable'                 => true ,
		                                        'in_admintable'            => true ,
		                                        'html_table_element'       => 'value' ,
		                                        'html_table_element_param' => array () ,
		                                        'html_edit_element'        => 'edit' ,
		                                        'html_edit_element_param'  => array () , ) ,
		               'created_at'  => array ( 'name'                     => 'created_at' ,
		                                        'editable'                 => true ,
		                                        'in_admintable'            => true ,
		                                        'html_table_element'       => 'value' ,
		                                        'html_table_element_param' => array () ,
		                                        'html_edit_element'        => 'edit' ,
		                                        'html_edit_element_param'  => array () , ) ,
		               'user_id'     => array ( 'name'                     => 'user_id' ,
		                                        'editable'                 => true ,
		                                        'in_admintable'            => true ,
		                                        'html_table_element'       => 'value' ,
		                                        'html_table_element_param' => array () ,
		                                        'html_edit_element'        => 'edit' ,
		                                        'html_edit_element_param'  => array () , ) );
	}

	public function get_tableinfo() {
		return array ( 'header_list' => 'CategoriesDetails' ,
		               'header_new'  => 'Создание CategoriesDetails' ,
		               'header_edit' => 'Редактирование CategoriesDetails' );
	}

	public static function get_picture_uploader( $item ) {

		Joosdocument::instance()->add_js_file( JPATH_SITE . '/media/js/valumsfileuploader/fileuploader.js' );

		$attachments = json_decode( $item->attachments , true );

		//Ищем картинки
		$images = array ();
		if ( isset( $attachments['images'] ) ) {
			$images = $attachments['images'];
		} else {
			$images[] = 1;
		}

		$js_code     = '';
		$return      = '<div id="content_uploader_area">';

		$img_counter = isset( $attachments['images'] ) ? count( $attachments['images'] ) : 1;
		$return .= '<input type="hidden" id="img_counter" name="img_counter" value="' . ( $img_counter + 1 ) . '" />';

		$i = 1;
		foreach ( $images as $tmp ) {

			//Изображение
			$image = isset( $images['image_' . $i] ) ? $images['image_' . $i] : array ();

			$return .= self::get_uploader( $item , $image , $i );

			//id изображения
			$image_id = $image ? $image['id'] : 0;
			$js_code .= self::get_js_code_for_uploader( $image_id , $i );
			$i++;
		}

		$return .= '</div><div class="b b-left b-33" style="padding:30px 0 0 0"><button type="button" id="add_pic">Ещё!</button></div></div>';

		Joosdocument::instance()->add_js_code( $js_code );

		return $return;
	}

	public static function get_uploader( $item , $image , $i ) {

		$return = '';

		//id изображения
		$image_id = $image ? $image['id'] : 0;
		//путь до изображения (директория)
		$image_path = $image ? $image['path'] : '';
		//если это изображение отмечено в качестве главного изображения
		$image_for_category = ( $image && isset( $image['main_image'] ) ) ? true : false;

		$return .= '<div class="b b-left b-33" style="padding:30px 0 0 0">';

		//Поля с данными об изображениях
		$return .= '<input type="hidden" id="image_' . $i . '_id" name="images[image_' . $i . '][id]" value="' . $image_id . '" />';
		$return .= '<input type="hidden" id="image_' . $i . '_path" name="images[image_' . $i . '][path]" value="' . $image_path . '" />';

		$id = 'contentimage_' . $i;
		if ( $image_id ) {
			$item->image = $image_path;
			$item->name  = '';
			$return .= CategoriesDetails::get_image( $item , 'thumb' , array ( 'width' => '150' ,
			                                                                   'id'    => $id ) );
		} else {
			$return .= CategoriesDetails::get_image_default( array ( 'width' => '150' ,
			                                                         'id'    => $id ) );
		}

		$return .= '<br/><div id="image_' . $i . '_controls"><div class="content_upload_button_wrap" id="file-uploader-content_' . $i . '"></div>
		<button rel="image_' . $i . '" class="content_delete_image" type="button">X</button>';

		$checked = $image_for_category ? 'checked="checked"' : '';
		$return .= '<input ' . $checked . ' type="checkbox" id="image_' . $i . '_for_category" name="images[image_' . $i . '][main_image]" value="1" />';
		$return .= '&nbsp;<label class="content_img_forcat">главная</label>';

		$return .= '</div></div>';

		return $return;
	}

	public static function get_js_code_for_uploader( $image_id , $counter = 1 ) {
		$js_code = "
			var uploader_$counter = new qq.FileUploader({
				element: $('#file-uploader-content_$counter')[0],
				multiple: false,
				action:   'ajax.index.php?option=categories&task=image_uploader' ,
				button_label: '<img src=\"/media/images/admin/gnome-logout.png\" />',
				params: {
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

	public static function get_image( $item , $type = 'thumb' , $image_attr = array () ) {

		$file_location = JPATH_SITE_IMAGES . '/' . $item->image . '/' . $type . '.jpg';

		$image_attr += array ( 'src'   => $file_location ,
		                       'title' => $item->name ,
		                       'alt'   => $item->name );
		return joosHtml::image( $image_attr );
	}

	public static function get_image_default( $image_attr = array () ) {

		$file_location = JPATH_SITE . '/media/images/noimg.jpg';
		$image_attr += array ( 'src' => $file_location ,
		                       'alt' => '' );
		return joosHtml::image( $image_attr );
	}

	public static function get_slug_editor_helper( $item ) {
		return '
			<input type="text" style="width: 80%;" class="text_area" size="100" value="' . $item->slug . '" name="slug" id="slug">
			<span class="g-pseudolink" id="category_slug_generator" obj_id="' . $item->id . '">Сформировать</span>
		';
	}

}
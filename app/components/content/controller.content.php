<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Content - Компонент управления контентом
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage Content
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsContent extends joosController {

	public static function action_before( $active_task ) {
		//joosBreadcrumbs::instance()
		//->add('Новости', $active_task == 'index' ? false : joosRoute::href('news'));
		//joosBreadcrumbs::instance()->add('Бренды', joosRoute::href('content'));
		//Метаинформация страницы
		//joosMetainfo::set_meta('content', '', '', array('title'=>'Коллекции'));
	}

	public static function index() {

		$cats = new Categories( 'content' );
		$cats = $cats->get_list( array ( 'where' => 'level=1' ) );


		return array ( 'cats' => $cats );
	}

	public static function category() {

		$slug   = self::$param['slug'];
		$result = self::_category( self::$param['slug'] );

		if ( strpos( $slug , 'collections' ) == 0 ) {
			$result['template'] = 'collections';
		}

		return $result;
	}

	public static function category_collections() {
		$result             = self::_category( 'collections' );

		$result['task']     = 'category';
		$result['template'] = 'collections';
		return $result;
	}

	public static function category_about() {
		return self::_category( 'about' );
	}

	private static function _category( $slug ) {

		$category = new Categories( 'content' );

		//TODO: ужыыыыысссс
		$pos = strpos( $slug , '/page/' );
		if ( $pos > 0 ) {
			$page_fragment       = substr( $slug , $pos );
			$page                = explode( '/' , $page_fragment );
			$page                = array_pop( $page );
			$slug                = str_replace( $page_fragment , '' , $slug );
			self::$param['page'] = $page;
		}

		$page           = isset( self::$param['page'] ) ? self::$param['page'] : 0;

		$category->slug = $slug;
		$category->find() ? null : self::error404();


		//Ближайшие потомки
		//количество
		$category_children = $category->get_children( $category->id , true );
		$count             = count( $category_children );

		$pager             = new joosPager( joosRoute::href( 'content_cats_paginate' , array ( 'slug' => $category->slug ) ) , $count , 6 , 5 );
		$pager->paginate( $page );

		//категории
		$category_children = array_slice( $category_children , $pager->offset , $pager->limit , true );

		//Подробная информация для категорий
		$category_details = new CategoriesDetails;
		//если есть дочерние категории, ищем детальную информации сразу для пачки категорий
		$children_details = null;
		if ( $category_children ) {
			$ids              = array_keys( $category_children );
			$ids[]            = $category->id;

			$children_details = $category_details->get_list( array ( 'where' => 'cat_id IN (' . implode( ',' , $ids ) . ' )' ) );
			$category_details = $children_details[$category->id];
		} //иначе - загружаем данные только текущей категории
		else {
			$category_details->load( $category->id );
		}


		$items   = null;
		$ef_data = null;

		//Путь от корня
		$path = null;
		if ( $category->level > 1 ) {

			$params           = new joosParams;
			$params->group    = 'content';
			$params->subgroup = 'default';
			$params->find();
			$params_from_cat = json_decode( $params->data , true );

			$sort            = $params_from_cat['item_sort'];
			$sort            = $sort == 1 ? 'id' : 'ordering';


			$path            = $category->get_path_from_root( $category->id , true );

			//Выбираем записи
			if ( $category->level == 4 ) {
				$content = new Content;
				$items   = $content->get_list( array ( 'where' => 'category_id = ' . $category->id ,
				                                       'order' => $sort ) );

				if ( $items ) {
					//Дополнительные поля записей
					$ef_data = new ExtrafieldsData();
					$ef_data = $ef_data->get_selector( array ( 'key'   => 'obj_id' ,
					                                           'value' => 'value' ) , array ( 'where' => 'field_id = 2 AND obj_id IN (' . implode( ',' , array_keys( $items ) ) . ')' ) );
				}
			}
		}

		Categories::set_breadcrumbs( $category , $path );

		//Метаинформация страницы
		joosMetainfo::set_meta( 'category' , 'item' , $category->id , array ( 'title' => $category->name ) );

		return array ( 'category'          => $category ,
		               'category_details'  => $category_details ,
		               'category_children' => $category_children ,
		               'children_details'  => $children_details ,
		               'path'              => $path ,
		               'items'             => $items ,
		               'ef_data'           => $ef_data ,
		               'pager'             => $pager );
	}

	public static function view() {

		$slug = self::$param['slug'];

		// формируем и загружаем просматриваемую запись
		$item       = new Content;
		$item->slug = $slug;
		$item->find() ? null : self::error404();

		$category = new Categories( 'content' );
		$category->load( $item->category_id );

		$category_details = new CategoriesDetails;
		$category_details->load( $category->id );

		$path = null;
		if ( $category->level > 1 ) {
			$path = $category->get_path_from_root( $category->id , true );
		}

		Categories::set_breadcrumbs( $category , $path , true );

		//материалы из той же категории

		$other_items = $item->get_list( array ( 'where' => 'state = 1 AND category_id = ' . $item->category_id ) );
		//$other_items = ArrayTools::get_next_prev($other_items, $item->id, 2, 1, true);
		//Дополнительные поля
		$ef = Content::get_extrafields( $item );

		joosBreadcrumbs::instance()->add( $item->title );

		// одно из вышеобозначенных действий зафиксировало ошибку, прекращаем работу
		if ( self::$error ) {
			return;
		}

		// устанавливаем заголовок страницы
		joosDocument::instance()->set_page_title( $item->title );

		$template = ( isset( $path[1] ) && $path[1]->id == 3 ) ? 'collections' : 'default';

		// передаём параметры записи и категории в которой находится запись для оформления
		return array ( 'template'         => $template ,
		               'category'         => $category ,
		               'category_details' => $category_details ,
		               'path'             => $path ,
		               'item'             => $item ,
		               'other_items'      => $other_items ,
		               'ef'               => $ef );
	}

}
<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * joosAcl - Библиотека управления правами и ролями пользователей
 * Системная библиотека
 *
 * @version    1.0
 * @package    Joostina.Libraries
 * @subpackage Libraries
 * @category   Libraries
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class joosAcl {

	private static $instance;
	/**
	 *
	 * @var Zend_Acl
	 */
	private static $acl;

	public static function instance( $isAdmin = false ) {
		if ( self::$instance === NULL ) {
			set_include_path( JPATH_BASE . '/core/libraries/' );
			require_once( 'Zend/Acl.php' );
			require_once( 'Zend/Acl/Role.php' );
			require_once( 'Zend/Acl/Resource.php' );
			self::$acl = new Zend_Acl;

			if ( !$isAdmin ) {
				global $my;
				$group_name = strtolower( $my->group_name );
				$m         = '_acl_' . $group_name;
				call_user_func_array( 'joosAcl::' . $m , array () );
			}
			self::$instance = new self;
		}
	}

	public static function acl() {
		return self::$acl;
	}

	public static function isAllowed( $obj , $task = null ) {

		if ( self::$instance === NULL ) {
			self::instance();
		}

		$user      = joosCore::user();

		$group_name = strtolower( $user->group_name );
		return self::$acl->isAllowed( $group_name , $obj , $task );
	}

	public static function isDeny( $obj , $task = null ) {
		return !self::isAllowed( $obj , $task );
	}

	/*
	 * Установка прав доступа для супер - администратора
	 */

	private static function _acl_superadministrator() {
		self::$acl->addRole( new Zend_Acl_Role( 'superadministrator' ) );

		self::$acl->add( new Zend_Acl_Resource( 'comments' ) ); // доступ к комментариям

		self::$acl->allow( 'superadministrator' , 'comments' ); // супер-админ может с комментариями всё
	}

	/*
	 * Установка прав доступа для администратора
	 */

	private static function _acl_admin() {
		self::$acl->addRole( new Zend_Acl_Role( 'administrator' ) );

		self::$acl->add( new Zend_Acl_Resource( 'comments' ) ); // доступ к комментариям

		self::$acl->allow( 'administrator' , 'comments' , array ( 'view' , 'add' , 'edit' , /* 'delete' */ ) ); // админ может с комментариями всё кроме удаления
	}

	/*
	 * Установка прав доступа для авторизованных пользователй фронта сайта
	 */

	private static function _acl_registered() {

		self::$acl->addRole( new Zend_Acl_Role( 'registered' ) );

		self::$acl->add( new Zend_Acl_Resource( 'comments' ) );

		self::$acl->allow( 'registered' , 'comments' , 'view' )->allow( 'registered' , 'comments' , 'add' );
	}

	/*
	 * Установка прав доступа для авторизованных пользователй фронта сайта
	 */

	private static function _acl_manager() {

		self::$acl->addRole( new Zend_Acl_Role( 'manager' ) );

		self::$acl->add( new Zend_Acl_Resource( 'comments' ) );

		self::$acl->allow( 'manager' , 'comments' );
	}

	/*
	 * Установка прав доступа для не авторизованных пользователй фронта сайта
	 */

	private static function _acl_guest() {
		$role = new Zend_Acl_Role( 'guest' );
		self::$acl->addRole( $role );

		$resource_comments = new Zend_Acl_Resource( 'comments' );
		self::$acl->add( $resource_comments );
		self::$acl->allow( 'guest' , 'comments' , 'view' ); // гость может смотреть комментарии
		//self::$acl->allow('guest', 'comments', 'add'); // но не может их добавлять
	}

	public static function init_admipanel() {

		self::instance( true );

		// собираем роли
		self::$acl->addRole( new Zend_Acl_Role( 'guest' ) )->addRole( new Zend_Acl_Role( 'registered' ) )->addRole( new Zend_Acl_Role( 'superadministrator' ) )->addRole( new Zend_Acl_Role( 'administrator' ) )->addRole( new Zend_Acl_Role( 'manager' ) )//->addRole( new Zend_Acl_Role('podmanager'), 'manager' ) /* это как бэ показывает что мы можем делать подгруппы */
			->addRole( new Zend_Acl_Role( 'editor' ) );

		// собираем ресурсы - компонентам
		self::$acl->add( new Zend_Acl_Resource( 'adminpanel' ) ) // вообще доступ в админку
			->add( new Zend_Acl_Resource( 'config' ) ) // глобальная конфигурации
		//->add(new Zend_Acl_Resource('plugins')) // расширения и хуки
			->add( new Zend_Acl_Resource( 'modules' ) ) // модули
		//->add(new Zend_Acl_Resource('filemanager')) // файловый менеджер
			->add( new Zend_Acl_Resource( 'installer' ) ) // установщик расширений
		//->add(new Zend_Acl_Resource('languages')) // управление языками
		//->add(new Zend_Acl_Resource('linkeditor')) // редактор ссылок на компоненты
			->add( new Zend_Acl_Resource( 'menumanager' ) ) // менеджер корневого меню
			->add( new Zend_Acl_Resource( 'pages' ) ) // управление страницами
			->add( new Zend_Acl_Resource( 'quickicons' ) ) // кнопками быстрого доступа
			->add( new Zend_Acl_Resource( 'templates' ) ) // управление шаблонами
		//->add(new Zend_Acl_Resource('trash')) // корзина
			->add( new Zend_Acl_Resource( 'users' ) ) // управление пользователями
		//->add(new Zend_Acl_Resource('cache')) // управление кешем
			->add( new Zend_Acl_Resource( 'blog' ) ); // блог


		self::$acl->deny( 'guest' ) // неавторизованным ничего нелья
			->deny( 'registered' ) // просто пользователям ничего нелья
			->allow( 'superadministrator' ); // суперадмину можно всё
	}

}
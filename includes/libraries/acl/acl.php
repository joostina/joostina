<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

class Jacl {

	private static $instance;
	private static $acl;

	public static function getInstance( $isAdmin = false ) {
		if (self::$instance === NULL) {
			set_include_path( JPATH_BASE.'/includes/libraries/acl/' );
			require_once('Zend/Acl.php');
			require_once('Zend/Acl/Role.php');
			require_once('Zend/Acl/Resource.php');
			self::$acl = new Zend_Acl;

			if( !$isAdmin ) {
				global $my;
				$groupname = strtolower($my->groupname);
				$m = '_acl_'.$groupname;
				call_user_func_array('Jacl::'.$m, array());
			}
			self::$instance = new self;
		}
	}

	public static function isAllowed( $obj, $task = null ) {
		global $my;

		if (self::$instance === NULL) {
			self::getInstance();
		}

		$groupname = strtolower($my->groupname);
		return self::$acl->isAllowed( $groupname ,$obj, $task );
	}

	public static function isDeny( $obj, $task = null ) {
		return !self::isAllowed($obj, $task);
	}

	/*
	 * Установка прав доступа для супер - администратора
	*/
	private static function _acl_superadministrator() {
		self::$acl->addRole( new Zend_Acl_Role('superadministrator') );

		self::$acl
				->add( new Zend_Acl_Resource('comments') ); // доступ к комментариям

		self::$acl
				->allow('superadministrator', 'comments'); // супер-админ может с комментариями всё
	}

	/*
	 * Установка прав доступа для администратора
	*/
	private static function _acl_admin() {
		self::$acl->addRole( new Zend_Acl_Role('administrator') );

		self::$acl
				->add( new Zend_Acl_Resource('comments') ); // доступ к комментариям

		self::$acl
				->allow('administrator', 'comments', array('view','add','edit', /* 'delete' */ ) ); // админ может с комментариями всё кроме удаления
	}

	/*
	 * Установка прав доступа для авторизованных пользователй фронта сайта
	*/
	private static function _acl_registered() {

		self::$acl->addRole( new Zend_Acl_Role('registered') );

		self::$acl
				->add( new Zend_Acl_Resource('comments') );

		self::$acl
				->allow('registered', 'comments', 'view')
				->allow('registered', 'comments', 'add');
	}

	/*
	 * Установка прав доступа для авторизованных пользователй фронта сайта
	*/
	private static function _acl_manager() {

		self::$acl->addRole( new Zend_Acl_Role('manager') );

		self::$acl
				->add( new Zend_Acl_Resource('comments') );

		self::$acl
				->allow('manager', 'comments');
	}

	/*
	 * Установка прав доступа для не авторизованных пользователй фронта сайта
	*/
	private static function _acl_guest() {
		$role= new Zend_Acl_Role('guest');
		self::$acl->addRole($role);

		$resource_comments = new Zend_Acl_Resource('comments');
		self::$acl->add($resource_comments);
		self::$acl->allow('guest', 'comments', 'view'); // гость может смотреть комментарии
		//self::$acl->allow('guest', 'comments', 'add'); // но не может их добавлять

	}


	public static function init_admipanel() {

		self::getInstance( true );

		// собираем роли
		self::$acl->addRole( new Zend_Acl_Role('guest') )
				->addRole( new Zend_Acl_Role('registered') )
				->addRole( new Zend_Acl_Role('superadministrator') )
				->addRole( new Zend_Acl_Role('administrator')  )
				->addRole( new Zend_Acl_Role('manager')  )
				//->addRole( new Zend_Acl_Role('podmanager'), 'manager' ) /* это как бэ показывает что мы можем делать подгруппы */
				->addRole( new Zend_Acl_Role('editor')  );

		// собираем ресурсы - компонентам
		self::$acl
				->add( new Zend_Acl_Resource('adminpanel'))     // вообще доступ в админку
				->add( new Zend_Acl_Resource('config'))          // глобальная конфигурации
				->add( new Zend_Acl_Resource('plugins'))	     // расширения и хуки
				->add( new Zend_Acl_Resource('modules'))        // модули
				->add( new Zend_Acl_Resource('filemanager'))     // файловый менеджер
				->add( new Zend_Acl_Resource('installer'))        // установщик расширений
				->add( new Zend_Acl_Resource('languages'))      // управление языками
				->add( new Zend_Acl_Resource('linkeditor'))       // редактор ссылок на компоненты
				->add( new Zend_Acl_Resource('menumanager'))   // менеджер корневого меню
				->add( new Zend_Acl_Resource('pages'))          // управление страницами
				->add( new Zend_Acl_Resource('quickicons'))       // кнопками быстрого доступа
				->add( new Zend_Acl_Resource('templates'))       // управление шаблонами
				->add( new Zend_Acl_Resource('trash'))           // корзина
				->add( new Zend_Acl_Resource('users'))           // управление пользователями
				->add( new Zend_Acl_Resource('cache'))           // управление кешем
				->add( new Zend_Acl_Resource('sqldump'))        // создание дампа SQL базы
				->add( new Zend_Acl_Resource('blog'));        // блог


		self::$acl
				->deny('guest') // неавторизованным ничего нелья
				->deny('registered') // просто пользователям ничего нелья
				->allow('superadministrator'); // суперадмину можно всё

		//_xdump(self::$acl);
		//exit();

	}


}
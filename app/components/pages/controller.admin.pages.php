<?php

// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Компонент управления независимыми страницами
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Pages
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminPages  extends joosAdminController{

	public static $submenu = array(
		'default' => array(
			'name' => 'Все страницы',
            'model'=>'modelAdminPages',
			'href' => 'index2.php?option=pages',
            'fields'=>array('title','created_at'),
			'active' => false
		),
	);

}
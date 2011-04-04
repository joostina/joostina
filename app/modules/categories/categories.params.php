<?php

/**
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

$extension_install = array(
	'type' => 'module',
	'name' => 'categories',
	'client_id' => '0',
	'position' => 'left'
);

$extension_access = array(
	'view' => array(
		'title' => 'Отображать',
		'rule' => 'first_rule',
		'groups' => array(8, 9)
	),
	'smth_action' => array(
		'title' => 'Какое-то действие',
		'rule' => 'second_rule',
		'groups' => array(9)
	)
);

$extension_info = array(
	'author' => 'Joostina Team',
	'creationDate' => '2010',
	'copyright' => '(C) 2008-2010 Joostina team.',
	'license' => 'http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL',
	'authorEmail' => 'joostinacms@gmail.com',
	'authorUrl' => 'www.joostina.ru',
	'version' => '1.0',
	'description' => 'Модуль вывода категорий'
);

$extension_params = array(
	'group' => array(
		'name' => 'Группа',
		'editable' => true,
		'html_edit_element' => 'edit',
		'html_edit_element_param' => array()
	),
	'level' => array(
		'name' => 'Уровень',
		'editable' => true,
		'html_edit_element' => 'edit',
		'html_edit_element_param' => array()
	)
);
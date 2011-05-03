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

class adminmenuHelper {

	//Отдаем пункты меню
	public static function get_items() {

		return array(
			'Стартовая' => array(
				'title' => '',
				'href' => 'index2.php'
			),
			'Контент' => array(
				'title' => '',
				'href' => '/',
				'children' => array(
					'Независимые страницы:' => array(
						'href' => '/',
						'ico' => '',
						'type' => 'sep'
					),
					'Все страницы' => array(
						'href' => 'index2.php?option=pages',
						'ico' => 'ico-pages',
					),
					'Добавить страницу' => array(
						'href' => 'index2.php?option=pages&task=create',
						'ico' => 'ico-page_add',
					),
					'Структурированный контент:' => array(
						'href' => '/',
						'ico' => '',
						'type' => 'sep'
					),
					'Все материалы' => array(
						'href' => 'index2.php?option=content',
						'ico' => 'ico-articles',
					),
					/* 'По категориям' => array(
					  'href' => '/',
					  'ico' => 'ico-articles_bycat',
					  ), */
					'Добавить материал' => array(
						'href' => 'index2.php?option=content&task=create',
						'ico' => 'ico-article_add',
					),
					'Категории:' => array(
						'href' => '/',
						'ico' => '',
						'type' => 'sep'
					),
					'Все категории' => array(
						'href' => 'index2.php?option=categories&group=content',
						'ico' => 'ico-cats',
					),
					'Добавить категорию' => array(
						'href' => 'index2.php?option=categories&group=content&task=create',
						'ico' => 'ico-cat_add',
					),
				),
			),
			'Пользователи' => array(
				'title' => '',
				'href' => '/',
				'children' => array(
					'Все пользователи' => array(
						'href' => 'index2.php?option=users',
						'ico' => 'ico-users',
					),
				/* 'Группы пользователей' => array(
				  'href' => 'index2.php?option=users&model=UsersGroups',
				  'ico' => 'ico-users',
				  ),
				  'Управление правами' => array(
				  'href' => '/',
				  'ico' => 'ico-acl',
				  ),
				  'Настройки подсистем' => array(
				  'href' => '/',
				  'ico' => 'ico-config',
				  ), */
				)
			),
			/* 'Меню' => array(
			  'title'=>'',
			  'href'=>'/',
			  'children'=>array(
			  'Менеджер меню' => array(
			  'href' => '/',
			  'ico' => '',
			  ),
			  'Добавить меню' => array(
			  'href' => '/',
			  'ico' => '',
			  ),
			  'Существующие меню:' => array(
			  'href' => '/',
			  'ico' => '',
			  'type'=>'sep'
			  ),
			  'Первое меню' => array(
			  'href' => '/',
			  'ico' => '',
			  ),
			  'Второе меню' => array(
			  'href' => '/',
			  'ico' => '',
			  ),
			  )
			  ), */

			'Компоненты' => array(
				'title' => '',
				'href' => '/',
				'children' => array(
					'Новости' => array(
						'href' => 'index2.php?option=news',
						'ico' => '',
					),
					'Опросы' => array(
						'href' => 'index2.php?option=polls',
						'ico' => '',
					),
					'Блоги' => array(
						'href' => 'index2.php?option=blog',
						'ico' => '',
					),
				)
			),
			'Центр расширений' => array(
				'title' => '',
				'href' => '/',
				'children' => array(
					/* 'Компоненты' => array(
					  'href' => '/',
					  'ico' => 'ico-component',
					  ), */
					'Модули' => array(
						'href' => 'index2.php?option=modules',
						'ico' => 'ico-component',
					),
				/* 'Плагины' => array(
				  'href' => '/',
				  'ico' => 'ico-component',
				  ),
				  'Шаблоны' => array(
				  'href' => 'index2.php?option=templates',
				  'ico' => 'ico-template',
				  ),
				  'Языки' => array(
				  'href' => '/',
				  'ico' => 'ico-lang',
				  ),
				  'Установка расширений' => array(
				  'href' => 'index2.php?option=installer',
				  'ico' => 'ico-manager',
				  ), */
				)
			),
			/* 'Настройки' => array(
			  'title'=>'',
			  'href'=>'/',
			  'children'=>array(
			  'Настройки сайта:' => array(
			  'href' => '/',
			  'ico' => '',
			  'type'=>'sep'
			  ),
			  'Конфигурация сайта' => array(
			  'href' => '/',
			  'ico' => '',
			  ),
			  'Позиции модулей' => array(
			  'href' => 'index2.php?option=templates&task=positions',
			  'ico' => '',
			  ),

			  'Настройки админ-панели:' => array(
			  'href' => '/',
			  'ico' => '',
			  'type'=>'sep'
			  ),
			  'Конфигурация админ-панели' => array(
			  'href' => '/',
			  'ico' => '',
			  ),

			  'Модули админ-панели' => array(
			  'href' => '/',
			  'ico' => '',
			  ),
			  'Меню компонентов' => array(
			  'href' => '/',
			  'ico' => '',
			  ),

			  )
			  ), */

			'Инструменты' => array(
				'title' => '',
				'href' => '/',
				'children' => array(
					/* 'Файловый менеджер' => array(
					  'href' => '/',
					  'ico' => '',
					  ),
					  'Менеджер БД' => array(
					  'href' => '/',
					  'ico' => '',
					  ),
					  'Управление кэшем' => array(
					  'href' => '/',
					  'ico' => '',
					  ),
					  'Инструменты оптимизации' => array(
					  'href' => '/',
					  'ico' => '',
					  ),
					  'Резервное копирование' => array(
					  'href' => '/',
					  'ico' => '',
					  ), */
					'Кодер' => array(
						'href' => 'index2.php?option=coder',
						'ico' => '',
					),
				/* 'Сборщик системы' => array(
				  'href' => '',
				  'ico' => '',
				  ),
				  'Системная корзина' => array(
				  'href' => '/',
				  'ico' => '',
				  ), */
				)
			),
			'Информация' => array(
				'href' => '/',
				'ico' => '',
				'children' => array(
					'Сводные данные' => array(
						'href' => '/',
						'ico' => '',
					),
					'Информация о системе' => array(
						'href' => '',
						'ico' => '',
					),
					'Связь с разработчиками' => array(
						'href' => '/',
						'ico' => '',
					),
					'Скачать расширения и шаблоны' => array(
						'href' => '/',
						'ico' => '',
					),
					'Сайт поддержки' => array(
						'href' => 'http://forum.joostina.ru',
						'ico' => '',
					),
				)
			),
		);
	}

}

/**

$menu_items = array(
'Сайт' => array(
'Глобальная конфигурация' => array(
'href' => '',
'ico' => '',
),
'Еще что-то' => array(
'href' => '',
'ico' => '',
),
'Расширения' => array(
'children' => array(
'Компоненты' => array(
'href' => '',
'ico' => '',
),
'Модули' => array(
'href' => '',
'ico' => '',
),
'Плагины и хуки' => array(
'href' => '',
'ico' => '',
),
'Шаблоны и сниппеты' => array(
'href' => '',
'ico' => '',
),
'Языки и локализации' => array(
'href' => '',
'ico' => '',
),
'__',
'Установка расширений' => array(
'href' => '',
'ico' => '',
),
),
),
),
'Пользователи' => array(
'children' => array(
'Все пользователи' => array(
'tip' => 'Список пользователй с возможностью фильтрации, вывода неактивированных, быстрое восстановление пароля через отправку его на email',
'href' => '',
'ico' => '',
),
'Добавить пользователя' => array(
'href' => '',
'ico' => '',
),
'Настройки регистрации' => array(
'tip' => 'Правила проверок, активация капчи, подтверждения пароля, тексты правил, соглашений, проверка возраста, тексты email-уведомлений',
'href' => '',
'ico' => '',
),
'Рассылка по пользователям' => array(
'tip' => 'Массовая отправка новостей, обновлений и информации группам, типам и конкретным пользователям',
'href' => '',
'ico' => '',
),
),
),
'Меню' => array(
'Создать новое' => array(
'tip' => 'При нажатии на ссылку сразу создаётся новое меню, и пользователь переправляется в создание пунктов этого меню',
'href' => '',
'ico' => '',
),
'call_from' => 'joosPlugin::get_menus', // вывод списка меню формируется сторонней функцией, возвращающей уже сформированный код
),
'Компоненты' => array(
'call_from' => 'joosAdmin::get_components_menu', // вывод списка меню компогнентов реализуется внешней настраиваемой функцией
'__',
'Редактировать этот список' => array(
'href' => '',
'ico' => '',
),
'Управление расширениями' => array(
'href' => '',
'ico' => '',
),
),
'Инструменты' => array(
'Полная очистка кэша' => array(
'href' => '',
'ico' => '',
),
'Очистка кэша по группам, тэгам' => array(
'href' => '',
'ico' => '',
),
'Проверка и оптимизация базы данных' => array(
'href' => '',
'ico' => '',
),
'__',
'Статистика' => array(
'Поиск по сайту' => array(
'href' => '',
'ico' => '',
),
'Рейтинг в поисковых системах' => array(
'href' => '',
'ico' => '',
),
'Отчеты о посещаемости' => array(
'href' => '',
'ico' => '',
),
),
'Системная корзина' => array(
'href' => '',
'ico' => '',
),
),
'Разработка' => array(
'Моделегенератор' => array(
'href' => '',
'ico' => '',
),
'SQL управлятог' => array(
'href' => '',
'ico' => '',
),
'БазаФакер' => array(
'href' => '',
'ico' => '',
),
'Сборщик системы' => array(
'href' => '',
'ico' => '',
),
),
'Информация' => array(
'Сводные данные' => array(
'href' => '',
'ico' => '',
),
'Информация о системе' => array(
'href' => '',
'ico' => '',
),
'Связь с разработчиками' => array(
'href' => '',
'ico' => '',
),
'Скачать расширения и шаблоны' => array(
'href' => '',
'ico' => '',
),
'Сайт поддержки' => array(
'href' => '',
'ico' => '',
),
),
);
 */
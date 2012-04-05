<?php

return array(
	'live_site' => 'http://www.joostina.local',
	'debug' => false,
	'404_page' => false,
	'template' => 'bootstrap',
	'template_admin' => 'joosbootstrap',
	'admin_icons_path' => '../media/images/admin/',
	'db' => array(//'host' => 'p:localhost', - для постоянного соединения
		'host' => 'localhost',
		'name' => 'joostina',
		'prefix' => 'jos_',
		'user' => 'root',
		'password' => '',
		'port' => '',
		'profiling_history_size' => 150,
        'charset'=>'utf8',
	),
	'cache' => array(
		'enable' => false,
		'handler' => 'file', // 'apc', 'file', 'memcache', 'xcache'
		'admin_menu_cache' => false,
		'cachepath' => JPATH_BASE . '/cache/cache.php', // TODO нехорошо
		'memcache_host' => '127.0.0.1',
		'memcache_port' => 11211
	)
	,
	'ajax' => array(
		'fullajax' => false, // сайт полностью на Ajax
		'component_dom' => 'component', // название элемента в DOM-дереве для вывода оснвоного содержимого компонента при Аякс - запросах
	),
	'secure' => array(
		'secret_code' => 'joostina-cool-cms'
	),
	'info' => array(
		'title' => 'Joostina CMS!',
		'description' => 'Joostina CMS - система управления интересными сайтами',
		'keywords' => 'joostina, php, mysql, cms'
	),
	'session' => array(// тип сессииб 3 - самый безопасный
		'type' => 3,
		// время жизни сессии администратора
		'life_admin' => 1800, // 3 часа
	),
	'locale' => array(
		'offset' => 5,
	),
	'admin' => array(// число объектов по умолчанию на страницах списков
		'list_limit' => 25,),
	'mail' => array(
		'from' => 'admin@joostina.ru',
		// адрес, от чьего имени отправляются письма
		'system_email' => 'mail@joostina.ru',
		'name' => 'Joostina CMS',
		'type' => 'sendmail', // smtp || sendmail,
		'smtp_auth' => '',
		'smtp_user' => '',
		'smtp_pass' => '',
		'smtp_host' => '', // Порт для smtp указывается через двоеточие localhost:443
	),
	'logging' => array(
		'debug' => array(
			/*'stream' => array(
				'level' => joosLoggingLevels::DEBUG,
				'stream' => JPATH_BASE . DS . 'logs' . DS . 'example.log'
			),*/
			'mail' => array(
				'level' => joosLoggingLevels::ERROR,
				'to' => 'mail@example.com',
				'subject' => 'Message from monolog',
				'from' => 'mail@example.com'
			)
		)
	)
);

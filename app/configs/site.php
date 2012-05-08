<?php

return array(
    'live_site' => 'http://www.joostina.local',
    'debug' => true,
    'debug_template' => false, //в режиме true будут использованы less-файлы
    '404_page' => false,
    'template' => 'bootstrap',
    'template_admin' => 'joosbootstrap',
    'admin_icons_path' => '../media/images/admin/',
    'db' => array(
        //'host' => 'p:localhost', - для постоянного соединения
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
        'js_cache' => false, //при установке в true JS-файлы будут минимизированы и склеены в один файл
        'cachepath' => JPATH_BASE . '/cache/cache.php', // TODO нехорошо
        'memcache_host' => '127.0.0.1',
        'memcache_port' => 11211
    ),
    'secure' => array(
        'secret_code' => 'joostina-cool-cms'
    ),
    'info' => array(
        'title' => 'Joostina CMS!',
        'description' => 'Joostina CMS - система управления интересными сайтами',
        'keywords' => 'joostina, php, mysql, cms'
    ),
    'session' => array(
        // тип сессий 3 - самый безопасный
        'type' => 3,
        // время жизни сессии администратора
        'life_admin' => 1800, // 3 часа
    ),
    'locale' => array(
        'offset' => 5,
    ),
    'admin' => array(// число объектов по умолчанию на страницах списков
        'list_limit' => 25
    ),
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
);

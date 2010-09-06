<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'routemap.php';

/* Automatically find controllers */
$config = array('ctrl_dir' => dirname(__FILE__));

function __autoload($class_name) {
    global $config;
    require_once $config['ctrl_dir'] . '/' . strtolower($class_name) . '.php';
}

/* default handler
 * e.g. for URL: http://example.net
 */

function index() {
    print("Hello from the default route.");
}

$map = new RouteMap(array('url_rewriting' => true));

$map
        ->connect('default', '', NULL, 'index')
        ->connect('news_by_title', 'news/:title', 'News', 'show_title')
        ->connect('news_by_date', 'news/:year/:month/:day', 'News', 'show',
                array('year' => '(200[8-9]{1})|(201[0-9]{1})', 'month' => '(0[1-9]{1})|(1[0-2]{1})'),
                array('year' => date('Y'))
        )
        ->connect('news_by_year', 'news/:year', 'News', 'show_year', array('year' => '20\d{2}'));

# e.g. http://example.net/routemap/index.php?news/2008/01/01
$map->dispatch(trim($_SERVER['QUERY_STRING']) != '' ? $_SERVER['QUERY_STRING'] : ltrim($_SERVER['REQUEST_URI'], '/'));

//print $map->url_for('news_by_date', array('year'=>2030,'id'=>123) );
echo $map->url_for('news_by_title', array('title' => 'пам - парам', 'id' => 123));
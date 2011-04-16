<?php
/**
 * Закладки - любимые исполнители
 *
 * */

//Запрет прямого доступа
defined('_JOOS_CORE') or die();

if (!$bookmarks) {
    echo 'Здесь ничего нет';
    return;
}
joosLoader::lib('text');
?>



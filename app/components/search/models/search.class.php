<?php
/**
 * Search - Компонент поиска
 * Модель
 *
 * @version 1.0
 * @package Joostina.Components
 * @subpackage Search
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 **/
// запрет прямого доступа
defined('_JOOS_CORE') or die();

//Поддержка кастомных параметров
joosLoader::lib('params', 'system');

//Поддержка метаданных
joosLoader::lib('metainfo', 'seo');

//Содержимое модели
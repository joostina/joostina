<?php defined('_JOOS_CORE') or die();

/**
 * Компонент новостей - шаблон просмотра объекта
 *
 * @version    1.0
 * @package    Components\News
 * @subpackage Views
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
?>

<?php echo joosBreadcrumbs::instance()->get() ?>

<h1><?php echo $item->title ?></h1>

<div class="news-item_full">

    <div class="date">
        <?php echo joosDateTime::format($item->created_at, '%d %B %Y ')  ?>
    </div>

    <div class="item-body">
        <?php echo $item->fulltext ?>
    </div>

</div>




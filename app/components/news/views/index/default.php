<?php defined('_JOOS_CORE') or exit();

/**
 * Компонент новостей - шаблон просмотра стартовой страницы компонента
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
<h1>Новости</h1>

<ul class="news-list unstyled">
    <?php foreach ($news as $item):?>
    <li>
        <div class="date"><?php echo joosDateTime::format($item->created_at, '%d %B %Y ')  ?></div>
        <h2>
            <a class="news-title" href="<?php echo joosRoute::href('news_view', array('id' => $item->id ))  ?>">
                <?php echo $item->title ?>
            </a>
        </h2>

        <p><?php echo $item->introtext ?></p>
    </li>
    <?php endforeach;?>
</ul>

<?php echo $pager->output;?>



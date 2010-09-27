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

//Навигационная область раздела
//Отображается на странице профиля пользователя

$is_owner = false;
if ($my->id && $my->id == $user->id) {
    $is_owner = true;
}

$page_title = $is_owner ? 'Мой профиль' : 'Пользователь ' . $user->username;
?>
<h5><?php echo $page_title; ?></h5>
<div class="menu_inside_submenu">
    <ul class="menu_inside_submenu_ul active_ul">

        <li <?php echo isset($_is_it_view) ? 'class="menu_inside_submenu_active"' : '' ?>><span>
                <a title="Главная страница профиля" href="<?php echo sefRelToAbs('index.php?option=com_users&task=view&id=' . sprintf('%s:%s', $user->id, $user->username)); ?>">Главная</a>
            </span></li>

<?php if ($is_owner) : ?>
        <li <?php echo isset($_is_it_edit) ? 'class="menu_inside_submenu_active"' : '' ?>><span>
                <a title="Редактирование информации" href="<?php echo sefRelToAbs('index.php?option=com_users&task=edit', true) ?>">Редактирование</a>
            </span></li>
<?php endif; ?>

        <li <?php echo isset($_is_it_files) ? 'class="menu_inside_submenu_active"' : '' ?>><span>
                <a title="Файлы" href="<?php echo sefRelToAbs('index.php?option=com_users&task=files&id=' . sprintf('%s:%s', $user->id, $user->username), true) ?>">Файлы</a>
            </span></li>

        <li <?php echo isset($_is_it_bookmarks) ? 'class="menu_inside_submenu_active"' : '' ?>><span>
                <a title="Избранное" href="<?php echo sefRelToAbs('index.php?option=com_bookmarks&id=' . sprintf('%s:%s', $user->id, $user->username), true); ?>">Избранное</a>
            </span></li>

        <li <?php echo isset($_is_it_lovegames) ? 'class="menu_inside_submenu_active"' : '' ?>><span>
                <a title="Любимые игры" href="<?php echo sefRelToAbs('index.php?option=com_users&task=lovegames&id=' . sprintf('%s:%s', $user->id, $user->username), true); ?>">Любимые игры</a>
            </span></li>

<?php if ($is_owner) : ?>
        <li <?php echo isset($_is_it_watchgames) ? 'class="menu_inside_submenu_active"' : '' ?>><span>
                <a title="Отслеживаемые игры" href="<?php echo sefRelToAbs('index.php?option=com_users&task=watchgames&id=' . sprintf('%s:%s', $user->id, $user->username), true); ?>">Отслеживаемые игры</a>
            </span></li>
<?php endif; ?>

    </ul>
</div>



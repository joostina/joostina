<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

//Навигационная область раздела
//Отображается на странице списка пользователей
?>

<?php mosMainFrame::addLib('text') ?>
<h5><a href="<?php echo sefRelToAbs('index.php?option=users', true) ?>">Пользователи</a></h5>

<!--
    <ul class="menu_inside">
        <li class="menu_inside_active" id="by_statuses"><a href="#">самые разные</a></li>
        <li id="by_abc_ru"><a href="#">А - Я</a></li>
        <li id="by_abc_en"><a href="#">A - Z</a></li>
        <li id="by_abc_num"><a href="#">0 - 9 </a></li>
        <li id="by_search"><a href="#">поиск</a></li>
    </ul>
-->

<div class="menu_inside_submenu">
    <ul class="menu_inside_submenu_ul active_ul by_statuses">
        <li<?php echo isset($_is_it_all) ? ' class="menu_inside_submenu_active"' : '' ?>><span><a href="<?php echo sefRelToAbs('index.php?option=users',true) ?>">Все</a></span></li>
        <li<?php echo isset($_is_it_topusers) ? ' class="menu_inside_submenu_active"' : '' ?>><span><a href="<?php echo sefRelToAbs('index.php?option=users&task=top',true) ?>">TOP активистов</a></span></li>
        <li<?php echo isset($_is_it_admins) ? ' class="menu_inside_submenu_active"' : '' ?>><span><a href="#">Смотрители</a></span></li>
        <li<?php echo isset($_is_it_nubes) ? ' class="menu_inside_submenu_active"' : '' ?>><span><a href="#">Новички</a></span></li>
    </ul>
    <!--
<ul class="menu_inside_submenu_ul by_abc_ru">
         <li><span><a href="#">Все</a></span></li>
<?php foreach (Text::$abc_ru as $simbol) {
?><li><span><a href="#"><?php echo $simbol; ?></a></span></li><?php } ?>
     </ul>
     <ul class="menu_inside_submenu_ul by_abc_en">
         <li><span><a href="#">Все</a></span></li>
<?php foreach (Text::$abc_en as $simbol) {
?><li><span><a href="#"><?php echo $simbol; ?></a></span></li><?php } ?>
     </ul>
     <ul class="menu_inside_submenu_ul by_abc_num">
         <li><span><a href="#">Все</a></span></li>
<?php for ($index = 0; $index < 10; $index++) : ?>
             <li><span><a href="#"><?php echo $index; ?></a></span></li>
<?php endfor ?>
         </ul>
         <ul class="menu_inside_submenu_ul by_abc_num menu_search">
             <li>
                 <input type="text" id="search_user" />
                 <span class="button"><button type="button">найти</button></span>
             </li>
         </ul>
    -->
</div>


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


?>
<div class="page_users page">
    
    <?php require_once JPATH_BASE.'/components/com_users/views/navigation/default.php'; ?>

    <table class="content_table" id="sort_table" cellpadding="0" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>
                    <span class="th">ник</span>
                </th>
                <th width="110">
                    <span class="th">рейтинг </span>
                </th>
                <th width="100">
                    <span class="th">был онлайн</span>
                </th>
            </tr>
        </thead>
        <?php $k = 2; ?>
        <?php foreach ($users_list as $user): ?>
                <tr class="row<?php echo $k = 1 - $k; ?>">
                    <td>
                        <a class="avatar_mid" href="index.php?section=users&act=user"><img src="<?php echo User::avatar('_45x45', $user->id) ?>" alt="" /></a>
                        <a href="<?php echo sefRelToAbs('index.php?option=users&id=' . sprintf('%s:%s', $user->id, $user->username)) ?>"><?php echo $user->username ?></a>
                        <div class="rank"><?php echo $user->level > 0 ? 'геймер '.$user->level.'-го уровня' : 'новичок' ?></div>
                    </td>
                    <td>
                        <span class="fav"><?php echo $user->full_rate ? $user->full_rate : 0 ?></span>
                    </td>
                    <td><?php echo $user->lastvisitDate ?></td>
                </tr>        
        <?php endforeach ?>
            </table>

    <?php echo $pager->output ?>
    <div class="pagination_wrap">
        <div class="paginator" id="paginator"></div>
    </div>

</div>
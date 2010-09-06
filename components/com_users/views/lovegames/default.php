<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

mosMainFrame::addLib('files');
?>
<div class="page_user page">
    <?php require_once JPATH_BASE.'/components/com_users/views/navigation/profile.php'; ?>

    <?php if(!$game_list):?>
        <div class="notice">Ни одной любимой игры</div>

    <?php else:?>
    <table class="content_table" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <th class="th_thumb">&nbsp;</th>
            <th>                
                <span class="th">название</span> 
            </th>
            <th class="th_date">                
                <span class="th">дата выхода</span>
            </th>
            <th class="th_rate"> 
                <span class="th">рейтинг </span>
            </th>
        </tr>
<?php $k = 2; ?>
<?php foreach ($game_list as $game) : // игры  ?>
        <?php Games::easy_prepare($game); ?>
        <tr class="row<?php echo $k = 1 - $k; ?>">
            <td class="td_thumb">
                <a class="thumb" href="<?php echo $game->game_href ?>" title="<?php echo $game->title ?>"><img src="<?php echo JPATH_SITE_IMAGES, '/attachments/games/' . File::makefilename($game->image_id) ?>/game.png" alt="" /></a>
            </td>
            <td>
                <h2><a href="<?php echo sefRelToAbs('index.php?option=com_games&task=game&id=' . sprintf('%s:%s', $game->id, $game->title)) ?>"><?php echo $game->title ?></a></h2>
                жанры <?php echo $game->ganres_hrefs ?>
                <br />
                платформы: <?php echo $game->platforms_hrefs ?>
            </td>
            <td class="td_date"><?php echo $game->date ?></td>
            <td class="td_rate"><span class="fav">7.8</span></td>
        </tr>
<?php endforeach; // игры  ?>
    </table>

<?php echo $pager->output ?>
    <div class="pagination_wrap">
        <div class="paginator" id="paginator"></div>
    </div>
    
    <?php endif;?>

</div>
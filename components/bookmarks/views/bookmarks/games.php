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

$type_names = Topic::get_types_cat();
?>
<div class="page_bookmarks page">

    <?php require_once JPATH_BASE.'/components/com_users/views/navigation/profile.php'; ?>

<!--
    <div class="menu_inside_submenu">
        <ul class="menu_inside_submenu_ul active_ul by_types">
            <li <?php echo $type_name == '' ? 'class="menu_inside_submenu_active"' : '' ?>><span><a href="<?php echo sefRelToAbs('index.php?option=com_topic', true) ?>">Все</a></span></li>
            <?php foreach ($type_names as $type_id => $_type_name): // типы топиков     ?>
                <li <?php echo $type_name == $_type_name[0] ? 'class="menu_inside_submenu_active"' : '' ?>><span><a href="<?php echo sefRelToAbs('index.php?option=com_topic&task=' . $_type_name[1], true) ?>"><?php echo $_type_name[0] ?></a></span></li>
            <?php endforeach; // типы топиков   ?>
            </ul>
        </div>
-->

    <?php if(!$bookmarks_list) :?>
        <div class="notice">
            Ни одной закладки
        </div>
    <?php else:?>

        <?php foreach ($bookmarks_list as $topic): // статьи    ?>
        <?php Topic::prepare($topic); // подготавливаем внутренние данные для статей   ?>
                        <div class="news_item_wrap">
                            <span class="date"><em><?php echo $topic->date_info['month_name'] ?></em><strong class="date"><?php echo $topic->date_info['day'] ?></strong><?php echo $topic->date_info['year'] ?></span>
                            <span class="in_bookmarks to_bookmarks" obj_option="topic" obj_id="<?php echo $topic->id ?>">
                                <a title="В избранное" href="#" <?php echo isset(User::current()->extra()->bookmarks_cache['topic']['all'][$topic->id]) ? 'class="active"' : '' ?>>&nbsp;</a> <small>(<?php echo $topic->bookmarks_count ?>)</small>
                            </span>
        <span class="item_rater">
            <a class="rater_topic rater_plus_1<?php echo ( isset(User::current()->extra()->votes_cache['topic'][$topic->id]) && User::current()->extra()->votes_cache['topic'][$topic->id] > 0) ? ' active' : '' ?>" obj_id="<?php echo $topic->id ?>" title="Нравится" href="#">&nbsp;</a>
            <span class="item_rate obj_<?php echo $topic->id ?>"><?php echo $topic->votesresults ? $topic->votesresults : 0 ?></span>
            <a class="rater_topic rater_minus_1<?php echo ( isset(User::current()->extra()->votes_cache['topic'][$topic->id]) && User::current()->extra()->votes_cache['topic'][$topic->id] < 0) ? ' active' : '' ?>" obj_id="<?php echo $topic->id ?>" title="Не нравится" href="#">&nbsp;</a>
        </span>
                            <div class="news_item">
                                <h2>
                                	<?php if($topic->game_id):?>
                                    <a  href="<?php echo sefRelToAbs('index.php?option=games&&task=game&id=' . sprintf('%s:%s', $topic->game_id, $topic->gamename), true) ?>" title="<?php echo $topic->gamename ?>"><?php echo $topic->gamename ?></a>
                                    &rarr;
                                    <?php endif;?>
                                     <a href="<?php echo sefRelToAbs('index.php?option=com_topic&task=' . $topic->type_names_alias, true) ?>" title="<?php echo $topic->type_name ?>"><?php echo $topic->type_name ?></a>
                                    &rarr; <a class="user_action" user_id="<?php echo $topic->user_id ?>" topic_id="<?php echo $topic->id ?>" href="<?php echo $topic->view_href ?>"><?php echo $topic->title ?></a></h2>
                <?php if ($topic->anons_image): ?>
                            <a class="thumb" href="<?php echo $topic->view_href ?>"><img src="<?php echo $topic->anons_image ?>" alt="<?php echo $topic->title ?>"/></a>
                <?php endif; ?>
                            <p><?php echo Text::word_limiter( $topic->anons, 50) ?></p>
                            <ul class="item_attr">
                                <li class="item_comments">
                                    <a href="<?php echo $topic->view_href ?>#comments" class="comments_total_link"><?php echo $topic->comment_count ? $topic->comment_count : 0 ?></a>
                                </li>
                                <li class="item_author">
                                    <a class="avatar" href="<?php echo sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $topic->user_id, $topic->user_login)) ?>" title="<?php echo $topic->user_login ?>"><img src="<?php echo User::avatar('_45x45', $topic->user_id) ?>" alt="<?php echo $topic->user_login ?>" /></a>
                                    сообщил<br /> <a class="username" href="<?php echo sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $topic->user_id, $topic->user_login)) ?>" title="<?php echo $topic->user_login ?>"><?php echo $topic->user_login ?></a>
                                </li>
                                <li class="item_tags">
                                    <span class="tag"><?php echo $topic->tags_hrefs ?></span>
                                </li>
    
                            </ul>
                        </div>
                    </div>
        <?php endforeach; // статьи    ?>
        <?php echo $pager->output ?>
        <div class="pagination_wrap">
            <div class="paginator" id="paginator"></div>
        </div>
        
    <?php endif;?>

</div>
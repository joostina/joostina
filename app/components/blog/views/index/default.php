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

if (!$blog_items) {
    echo 'Здесь ничего нет';
    return;
}

?>
<h3 class="g-blocktitle_blue"><a class="g-blocktitle_link" href="<?php echo joosRoute::href('blog') ?>">Блоги</a></h3>
<?php foreach ($blog_items as $blog_item): ?>
<?php $href = joosRoute::href('blog_view', array('id' => $blog_item->id, 'cat_slug' => $blog_item->cat_slug)); ?>
<?php $user_href = joosRoute::href('user_view', array('id' => $blog_item->userid, 'username' => $blog_item->username)) ?>
<?php $edit_href = joosRoute::href('blog_edit', array('id' => $blog_item->id)) ?>
<?php $created_at = joosDateTime::format($blog_item->created_at) ?>
	<ul class="listreset m-blogs_list">
		<li class="m-blogs_list_item">
            <h4 class="title_item"><a class="title_item_link" href="<?php echo $href ?>"
                                      title="<?php echo $blog_item->title ?>"><?php echo $blog_item->title ?></a></h4>

            <p class="m-blogs_item_text"><?php echo joosText::character_limiter(joosText::strip_tags_smart($blog_item->fulltext), 300) ?></p>

            <div class="m-blogs_program">
                <span class="el-user"><a href="<?php echo $user_href ?>"
                                         title="<?php echo $blog_item->username ?>"><?php echo $blog_item->username ?></a></span>
                <span class="el-date"><?php echo $created_at ?></span>
                <a href="<?php echo $href ?>#comments"
                   class="el-comments"><?php echo isset($blog_item->comments) ? $blog_item->comments : 'нет комментариев' ?></a>
                <?php echo $blog_item->state == 0 ? '<span class="error">Не опубликовано</span>' : '' ?>
                <?php echo (joosCore::user()->id == $blog_item->userid || joosCore::user()->gid == 8) ? '<span class="el-edit"><a href="' . $edit_href . '" title="Редактировать">Редактировать</a></span>' : '' ?>
                <?php echo joosVoter::controls('blog', $blog_item->id, $blog_item->votesresults) ?>
                <?php echo Bookmarks::addlink(null, array('class' => 'Blogs', 'id' => $blog_item->id)) ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<?php echo $pager->output; ?>
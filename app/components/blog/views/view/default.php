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

// обрабатываем все внешние ссылки
$blog->fulltext = joosText::outlink_parse($blog->fulltext);

//картинко
$image = Blog::get_image($blog);
?>
<h1><?php echo $blog->title ?></h1>
<p><?php echo $image != false ? $image : '' ?></p>
<div class="m-blogs_list_item full_item">
    <div class="m-blogs_item_text">
        <p><?php echo $blog->fulltext ?></p>
    </div>
    <div class="m-blogs_program">
        <span class="el-user"><a
                href="<?php echo joosRoute::href('user_view', array('username' => $user->username, 'id' => $user->id)) ?>"><?php echo $user->username ?></a></span>
        <span class="el-date"><?php echo joosDateTime::format($blog->created_at) ?>.</span>

        <?php echo $blog->state == 0 ? '<span class="error">Не опубликовано</span>' : '' ?>
        <?php $edit_href = joosRoute::href('blog_edit', array('id' => $blog->id)) ?>
        <?php echo (Users::current()->id == $blog->user_id || Users::current()->gid == 8) ? '<span class="el-edit"><a href="' . $edit_href . '" title="Редактировать">Редактировать</a></span>' : '' ?>
        <?php echo joosVoter::controls('blog', $blog->id, $blog->votesresults) ?>
        <?php echo Bookmarks::addlink(null, array('class' => 'Blogs', 'id' => $blog->id)) ?>
    </div>
</div>
<a name="comments"></a>
<?php echo $comments->load_comments_tree($blog) ?>
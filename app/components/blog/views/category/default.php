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

joosLoader::lib('text');
joosLoader::lib('voter', 'joostina');
joosLoader::model('bookmarks');
?>
<h1><?php echo $blog_category->title ?></h1>
<ul class="listreset m-blogs_list">
	<?php foreach ($blog_items as $blog_item): ?>
		<?php $href = joosRoute::href('blog_view', array('id' => $blog_item->id, 'cat_slug' => $blog_item->cat_slug)); ?>
		<?php $user_href = joosRoute::href('user_view', array('id' => $blog_item->userid, 'username' => $blog_item->username)) ?>
		<?php $image = Blog::get_image($blog_item, '100x100', array('class' => 'g-thumb_170x170')); ?>
		<?php $edit_href = joosRoute::href('blog_edit', array('id' => $blog_item->id)) ?>
		<li class="m-blogs_list_item">
			<?php if ($image): ?>
				<div class="g-thumb_170x170 g-blog-thumb">
					<?php echo $image ?>
				</div>
			<?php endif; ?>
			<h4 class="title_item"><a class="title_item_link" href="<?php echo $href ?>" title="<?php echo $blog_item->title ?>"><?php echo $blog_item->title ?></a></h4>
			<p class="m-blogs_item_text"><?php echo Text::character_limiter(Text::strip_tags_smart($blog_item->fulltext), 300) ?></p>
			<div class="m-blogs_author">
				<a href="<?php echo joosRoute::href('user_view', array('username' => $blog_item->username)) ?>" class="m-blogs_author_link">
				<img class="g-thumb_40 g-user_avatar" src="<?php echo User::avatar($blog_item->userid, '75x75') ?>" alt="<?php echo $blog_item->username ?>"/>
				<span class="el-user"><?php echo $blog_item->username ?></span>
				</a>
				<span class="el-date"><?php echo joosDate::format($blog_item->created_at) ?></span>
				<a href="<?php echo $href ?>#comments" class="el-comments"><?php echo isset($blog_item->comments) ? $blog_item->comments : 'нет комментариев' ?></a>

				<?php echo (User::current()->id == $blog_item->userid || User::current()->gid == 8) ? '<span class="el-edit"><a href="' . $edit_href . '" title="Редактировать">Редактировать</a></span>' : '' ?>
				<?php echo joosVoter::controls('blog', $blog_item->id, $blog_item->votesresults) ?>
				<?php echo Bookmarks::addlink(null, array('class' => 'Blogs', 'id' => $blog_item->id)) ?>
			</div>
		</li>
	<?php endforeach; ?>
</ul>
<?php echo $pager->output; ?>
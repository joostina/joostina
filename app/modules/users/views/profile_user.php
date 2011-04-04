<?php
/**
 * User - инфо по пользователю
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

$user_interests = $user->extra($user->id)->interests ? json_decode($user->extra($user->id)->interests) : null;
?>
<div class="m-artist_about  block_sidebar">
	<div class="g-blocktitle_sidebar_smaller m-artist_about_blocktitle">
		<?php if ($user->gid == 9) : echo Bookmarks::addlink_fan(null, array('class' => 'Fan_dj', 'id' => $user->id));
		endif; ?>
		<div class="g-thumb_75x75"><img class="g-thumb_75x75" src="<?php echo User::avatar($user->id, '75x75') ?>?<?php echo time() ?>" alt="<?php echo $user->username ?>" /></div>
		<h2 class="m-artist_about_title">
			<?php echo $user->username ?><br/>
			<span class="g-smaller"><?php echo $user->realname ? $user->realname : ''; ?></span>	<br/>
		</h2>

		<?php if ($user_interests): ?>
			<div class="m-artist_about_ganre">
				<span class="el-headfons">&nbsp;</span><?php echo implode(', ', $user_interests) ?>
			</div>
		<?php endif; ?>
	</div>

	<ul class="m-artist-nav listreset">

		<li class="m-artist-nav_item"><a class="m-artist-nav_link el-star<?php echo (joosController::$controller == 'bookmarks' && joosController::$task == 'index') ? ' g-active' : null ?>" href="<?php echo joosRoute::href('bookmarks_user', array('username' => $user->username)) ?>">Закладки</a></li>

	</ul>

	<?php if (User::current()->id == $user->id): ?>
		<a href="<?php echo joosRoute::href('user_edit', array('username' => User::instance()->username)) ?>" class="button">Настройки профиля</a>
		<a href="<?php echo joosRoute::href('blog_add') ?>" class="button">Написать в блог</a>
	<?php endif; ?>

</div>



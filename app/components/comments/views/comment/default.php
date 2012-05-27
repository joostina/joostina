<?php defined('_JOOS_CORE') or die(); ?>
<li class="comment depth-<?php echo $comment->level + 1 ?>">
	<span class="commentnumber"><?php echo $comment->id ?></span>

	<div id="comment-1" class="comment">
		<div class="comment-author">
			<img class="avatar" src="http://placehold.it/38x38" alt="">

			<div class="authormeta">
				<h3 class="comment-author">NickName 1</h3>
                        <span class="datetime">
                            <a href="/#comment-1" title="Commentlink #1">14 марта 2012, 18:12</a>
                        </span>
			</div>
		</div>

		<div class="comment-text">
			<p><?php echo $comment->comment_text ?></p>
		</div>

		<div class="commentmeta">
			<div class="reply">
				<a  href="#respond" data-comment-id="<?php echo $comment->id ?>" class="comment-reply-link">Ответить</a>
			</div>
		</div>
	</div>
</li>
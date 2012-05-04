<?php defined('_JOOS_CORE') or die();

?>
<div id="first_comment_wrap">
	<div class="comment_form" id="comment_form">
		<form action="" id="comments_addform">
			<textarea id="comment_input" name="comment_text" cols="10" rows="10" style="width: 98%"></textarea>
			<button class="comment_button button" type="submit">Добавить комментарий</button>
			<button class="button" id="comment_back" style="display: none">Отмена</button>
			<input type="hidden" name="parent_id" id="parent_id" value="0" />
		</form>
	</div>
</div>

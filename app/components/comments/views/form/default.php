<?php defined('_JOOS_CORE') or die();

?>

<div id="comments-form" class="clearfix">
    <form id="commentform" method="post" action="#">

        <div class="control-group">
            <label for="comment">Текст комментария <span class="required">*</span></label>
            <textarea class="span12" tabindex="4" rows="6" id="comment" name="comment"></textarea>
        </div>
        <button class="btn btn-success comment_button" type="submit">Отправить</button>

        <input type="hidden" name="parent_id" id="parent_id" value="0" />
        <input type="hidden" name="obj_option" id="obj_option" value="<?php echo $obj_option ?>" />
        <input type="hidden" name="obj_id" id="obj_id" value="<?php echo $obj_id ?>" />
    </form>
    </form>
</div>

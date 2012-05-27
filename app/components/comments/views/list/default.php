<?php defined('_JOOS_CORE') or die(); ?>

<?php //_xdump($comments_list);?>

<div id="comments">
    <ol class="commentlist unstyled">
	    <?php foreach ($comments_list as $comment): ?>
        <!--comment-->
            <?php helperComments::render_comment($comment) ?>

		<?php endforeach ?>
	        
        <li class="comment depth-1">
            <?php helperComments::render_comment($comment) ?>

            <ul class="children unstyled">
                <!--comment-->
                <li class="comment even depth-2 clearfix">
                    <?php helperComments::render_comment($comment) ?>
                </li>
            </ul>
        </li>

        <li class="comment depth-1">
            <?php helperComments::render_comment($comment) ?>

            <ul class="children unstyled">
                <!--comment-->
                <li class="comment depth-2 clearfix">
                    <?php helperComments::render_comment($comment) ?>
                </li>
	            
	            <ul class="children unstyled">
		            <!--comment-->
		            <li class="comment depth-3 clearfix">
			            <?php helperComments::render_comment($comment) ?>
		            </li>
	            </ul>
            </ul>
        </li>
    </ol>
</div>
<?php defined('_JOOS_CORE') or die();

?>

<div id="comments">
    <ol class="commentlist unstyled">
        <!--comment-->
        <li class="comment depth-1">
            <?php helperComments::render_comment(1) ?>
        </li>

        <li class="comment depth-1">
            <?php helperComments::render_comment(2) ?>

            <ul class="children unstyled">
                <!--comment-->
                <li class="comment even depth-2 clearfix">
                    <?php helperComments::render_comment(3) ?>
                </li>
            </ul>
        </li>

        <li class="comment depth-1">
            <?php helperComments::render_comment(4) ?>

            <ul class="children unstyled">
                <!--comment-->
                <li class="comment depth-2 clearfix">
                    <?php helperComments::render_comment(5) ?>
                </li>
            </ul>
        </li>
    </ol>
</div>
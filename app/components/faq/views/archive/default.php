<?php
/**

 *
 * */
defined('_JOOS_CORE') or die();


?>
<h1>Твой вопрос Yax! /
    <small>архив</small>
</h1>

<div class="archive_years">
    <?php foreach ($years as $y): ?>
    <?php if ($y == $year): ?>
        <span><?php echo $y?></span>
        <?php else: ?>
        <a href="<?php echo joosRoute::href('faq_archive_year', array('year' => $y));?>"><?php echo $y?></a>
        <?php endif; ?>

    <?php endforeach;?>
</div>

<div class="all_questions">
    <?php foreach ($items as $item) : ?>
    <div class="question_block">
        <div class="name"><b><?php echo $item->username ?></b> <span
                class="date">/ <?php echo joosDate::format($item->created_at, '%d.%m.%Y') ?></span></div>
        <div class="question">
            <?php echo $item->question ?>
            <div class="question_text">
                <p><b>ТМ YAX!</b></p>

                <p><?php echo $item->answer ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php echo $pager->output ?>



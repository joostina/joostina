<?php
/**

 *
 * */
defined('_JOOS_CORE') or die();


?>
<h1>Твой вопрос Yax!</h1>

<?php foreach ($items as $item) : ?>
<div class="question_block">
	<div class="name"><b><?php echo $item->username ?></b>  <span class="date">/ <?php echo joosDate::format($item->created_at, '%d.%m.%Y') ?></span></div>
	<div class="question">
		<?php echo $item->question ?>
		<div class="question_text">
			<p><b>ТМ YAX!</b></p>
			<p><?php echo $item->answer ?></p>
		</div>
	</div>
</div>
<?php endforeach; ?>
<a class="archive-link" href="<?php echo joosRoute::href('faq_archive') ?>">Архив вопросов</a>
<?php echo $pager->output ?>



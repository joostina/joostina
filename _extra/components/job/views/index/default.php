<?php
/**

 *
 * */
defined('_JOOS_CORE') or die();


?>
<h1>Вакансии</h1>

<div class="question_block">
	В настоящее время открыты следующие вакансии
	<?php foreach ($items as $item) : ?>

		<div class="question">
			<div class="q_title"><?php echo $item->title;?></div>
			<div class="q_text" style="display:none"><?php echo $item->fulltext;?></div>
		</div>
	<?php endforeach; ?>
</div>
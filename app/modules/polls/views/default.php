<?php
/**

 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

$questions = json_decode($poll->questions, true);
$variants = json_decode($poll->variants, true);

joosDocument::instance()
		->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.validate.js')
		->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.form.js')
		->add_js_file(JPATH_SITE . '/components/polls/media/js/polls.js');
?>
<div class="ask_block">
	<form action="/polls" method="post" class="poll_form" id="poll<?php echo $poll->id ?>">
		<fieldset>
			<div id="poll1_wrap">
				<h2>Какой системе вы отдаете предпочтение?</h2>
				<table>			
					<?php $i = 1;
					foreach ($questions as $q_id => $q): ?>
						<tr>
							<td><b><?php echo $q ?></b></td>
							<?php foreach ($variants as $v_id => $v): ?>
								<td>
									<input class="required" type="radio" class="radio" id="a<?php echo $i ?>" value="<?php echo $v_id ?>" name="question[<?php echo $q_id ?>]" /> 
									<label for="a<?php echo $i ?>"><?php echo $v ?></label>
								</td>
								<?php ++$i;
							endforeach; ?>
						</tr>
				<?php endforeach; ?>
				</table>
				<input type="hidden" name="poll_id" value="<?php echo $poll->id ?>" />
				<br /><input type="submit" value="Отправить" class="submit" /><span class="poll_errors" style="display: none;">Пожалуйста, сделайте выбор</span>
			</div>
		</fieldset>
	</form>
</div>
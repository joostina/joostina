<?php
/**

 *
 **/
 
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

joosDocument::instance()->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.validate.js');
joosDocument::instance()->add_js_code('$(".form_vacancy").validate({focusInvalid: false, errorLabelContainer: $("#errors")});');

joosDocument::instance()->add_js_code('
var params = {
		changedEl: ".lineForm select",
		visRows: 5,
		scrollArrows: true
	}

	cuSel(params);');
?>


<div class="right_form">
	<span class="respond_vacancies">ОТКЛИКНУТЬСЯ НА ВАКАНСИЮ</span>
	<?php echo joosFlashMessage::get() ?>
	<span id="errors" style="display: none;"></span>
	<form enctype="multipart/form-data" method="post" class="form_vacancy" action="<?php echo joosRoute::href('job_send_response') ?>">
		<fieldset>
			<label>Ваше имя<sup>*</sup>:</label>
			<input title="Введите имя" type="text" name="username" class="required" value="" />
			<label>Ваше e-mail<sup>*</sup>:</label>
			<input title="Введите Email" type="text" name="useremail" class="required" value="" />

			<label>Выбрать вакансию:</label>
			<div class="lineForm z_idex_01">
				<select title="Выберите вакансию" class="sel80 required" id="country" name="job_id" tabindex="2">
					<?php foreach($job as $key=>$val):?>
						<option title="<?php echo $val ?>" value="<?php echo $key ?>"><?php echo $val ?></option>
					<?php endforeach;?>
				</select>
			</div>
			<label>Сообщение<sup>*</sup>:</label>
			<textarea title="Введите текст сообщения"  name="message" class="required" cols="24" rows="6"></textarea>
			<label>Присоединить резюме<sup>*</sup>:</label>
			<div class="type_file">
				<input  name="qqfile" type="file" onchange="document.getElementById('fileName').value=this.value" class="inputFile" size="45">
				<div class="fonTypeFile"></div>
				<input title="Прикрепите резюме" class="required"  type="text" name="fileName" id="fileName" readonly="readonly" class="inputFileVal">
			</div>
			<div class="obligatory"><sup>*</sup> &mdash; Поля, обязательные для заполнения</div>
			<input type="submit" value="Отправить" class="go_enter">
		</fieldset>
	</form>
</div>

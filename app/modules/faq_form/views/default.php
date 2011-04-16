<?php
/**

 *
 **/

//Запрет прямого доступа
defined('_JOOS_CORE') or die();

joosDocument::instance()
        ->add_js_file(JPATH_SITE . '/media/js/jquery.plugins/jquery.validate.js')
        ->add_js_code('$(".form_vacancy").validate({focusInvalid: false, errorLabelContainer: $("#errors")});');

?>
<div class="right_form">
    <span class="respond_vacancies">Задайте нам вопрос</span>
    <?php echo joosFlashMessage::get() ?>
    <span id="errors" style="display: none;"></span>

    <form method="post" class="form_vacancy" action="<?php echo joosRoute::href('faq_send_question') ?>">
        <fieldset>
            <label>Ваше имя<sup>*</sup>:</label>
            <input title="Введите имя" type="text" name="username" class="required" value=""/>
            <label>Ваше e-mail<sup>*</sup>:</label>
            <input title="Введите Email" type="text" name="useremail" class="required" value=""/>

            <label>Вопрос<sup>*</sup>:</label>
            <textarea title="Введите вопрос" name="question" class="required" cols="24" rows="6"></textarea>

            <div class="obligatory"><sup>*</sup> &mdash; Поля, обязательные для заполнения</div>
            <input type="submit" value="Задать" class="go_enter">
        </fieldset>
    </form>
</div>

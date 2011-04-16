<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosDocument::instance()->add_js_code('$(".contact_form").validate({errorLabelContainer: $("#errors")});');

?>
<h1>Обратная связь</h1>
<?php joosModule::load_by_id(112) ?>
<?php echo joosFlashMessage::get() ?>
<div class="center_form_02">
    <form enctype="multipart/form-data" action="<?php echo joosRoute::href('contacts') ?>" name="contact_form"
          id="contact_form" method="post" class="contact_form form_validation">
        <span id="errors" style="display: none;"></span>
        <fieldset>
            <table>
                <col width="147px">
                <tbody>
                <tr>
                    <td><label>Ваше имя:</label></td>
                    <td><input name="username" id="username" type="text" class="text_input required" value=""></td>
                </tr>
                <tr>

                    <td><label>E-mail:</label></td>
                    <td><input type="text" name="usermail" id="usermail" value="" class="text_input required"></td>
                </tr>
                <tr>
                    <td><label>Тема:</label></td>
                    <td><input type="text" name="subject" id="subject" value="" class="text_input"></td>
                </tr>
                <tr>
                    <td><label>Сообщение:</label></td>
                    <td><textarea title="Введите текст сообщения" cols="24" rows="6" name="body" id="body"
                                  class="required"></textarea></td>
                </tr>
                <tr>
                    <td><label>Присоединить файл:</label></td>
                    <td>
                        <div class="type_file">
                            <input type="file" name="qqfile"
                                   onchange="document.getElementById(&quot;fileName&quot;).value=this.value"
                                   class="inputFile" size="45">

                            <div class="fonTypeFile"></div>
                            <input type="text" name="fileName" id="fileName" readonly="readonly" class="inputFileVal">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td><label>Проверочный код:</label></td>
                    <td>
                        <input class="required" title="Введите проверочный код" name="captcha" size="15" type="text"
                               style="width: 105px; float: left; margin: 0 10px 0 0;">
                        <img id="captchaimg" alt="Нажмите, чтобы обновить картинку"
                             onclick="document.contact_form.captchaimg.src='<?php echo JPATH_SITE; ?>/core/libraries/forms/kcaptcha/kcaptcha.php?session=<?php echo md5(JPATH_SITE) ?>&' + new String(Math.random())"
                             src="<?php echo JPATH_SITE; ?>/core/libraries/forms/kcaptcha/kcaptcha.php?session=<?php echo md5(JPATH_SITE) ?>"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Отправить" class="go_enter"></td>
                </tr>
                </tbody>
            </table>
        </fieldset>
    </form>
</div>














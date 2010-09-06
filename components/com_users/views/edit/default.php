<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

$mf = mosMainFrame::getInstance();

mosMainFrame::addClass('mosHTML');

$validator_vars = " <script type=\"text/javascript\">
                        var _validation_form = '#userForm';
                        var _validation_rules = " . $validator->get_js_validator('rules') . ";
                        var _validation_messages = " . $validator->get_js_validator('messages') . ";
                        var _upload_avatar = 1;
                    </script>";
$mf->addCustomHeadTag($validator_vars);
?>

<div class="page page_editprofile">
<?php require_once JPATH_BASE . '/components/com_users/views/navigation/profile.php'; ?>

    <div class="errors"><?php echo $user->getError(); ?></div>

    <form action="<?php echo sefRelToAbs('index.php?option=com_users&task=edit', true); ?>" method="post" name="userForm" id="userForm">

        <h6>Персональные данные:</h6>
        <dl>
            <dt><label>Настоящее имя:</label></dt><dd><input type="text" value="<?php echo $user_extra->realname ?>" name="realname" /></dd>

            <dt><label for="gender_f"><?php echo _C_USERS_GENDER ?></label></dt><dd>
<?php echo mosHTML::genderSelectList('gender', 'class="inputbox"', $user_extra->gender); ?> </dd>

            <dt><label>Дата рождения:</label></dt>
            <dd><input type="text" value="<?php echo $user_extra->birthdate ?>" name="birthdate" id="birthdate" /></dd>

            <dt><label>Местоположение:</label></dt>
            <dd><input type="text" value="<?php echo $user_extra->location ?>" name="location" /></dd>

            <dt><label>О себе:</label></dt>
            <dd><textarea name="about" cols="50" rows="5"><?php echo $user_extra->about ?></textarea></dd>
        </dl>

        <h6>Контактные данные:</h6>
        <dl>
            <dt><label>Email:</label></dt><dd><input type="text" value="<?php echo $user->email ?>" name="email" /></dd>
            <dt><label>Сайт:</label></dt><dd><input type="text" value="<?php echo $user_extra->site ?>" name="site" /></dd>
            <dt><label>ICQ:</label></dt><dd><input type="text" value="<?php echo $user_extra->icq ?>" name="icq" /></dd>
            <dt><label>Jabber:</label></dt><dd><input type="text" value="<?php echo $user_extra->jabber ?>" name="jabber" /></dd>
            <dt><label>Twitter:</label></dt><dd><input type="text" value="<?php echo $user_extra->twitter ?>" name="twitter" /></dd>
            <dt><label>Skype:</label></dt><dd><input type="text" value="<?php echo $user_extra->skype ?>" name="skype" /></dd>
        </dl>

        <h6>Смена пароля:</h6>
        <dl>
            <dt><label>Старый пароль:</label></dt>
            <dd><input type="text" value="" name="pass_old" /></dd>
            <dt><label>Новый пароль:</label></dt>
            <dd><input type="text" value="" name="pass_new" /></dd>
        </dl>

        <span class="button" id="save_profile"><input type="submit" value="Сохранить" class="button" /></span>
        <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />

        <div class="errors errors_ajax"></div>

    </form>

    <div class="avatar_edit">
        <h6>Аватар:</h6>

        <span class="button"  id="pic_av"><input type="button" value="Выбрать" id="pickfiles" class="button" /></span>
        <span id="process" class="plupload_file_status"></span> 

        <div id="avatar_wrap">
            <img class="avatar_edit_img" src="<?php echo User::current()->avatar() ?>" id="useravatar" />
        </div> 

    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("#birthdate").datepicker( {dateFormat: 'yy-mm-dd', firstDay: 1 , changeYear: true, yearRange: '1901:2010' } );
    });
</script>

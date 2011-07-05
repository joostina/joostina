<?php
/**
 * Профиль пользователя - редактирование
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

$validate_js_code =
		<<<EOD
        	var validator = $('.usereditform').validate({
		rules: {$validator->get_js_validator('rules')},
		messages: {$validator->get_js_validator('messages')},
		errorElement: 'div',
		errorClass: 'error',
		errorPlacement: function(error, element) {
			error.append('<em></em>');
			element.parent().append(error);
		},
		submitHandler: function(form) {
			ajax_useredit();
		},
		success: function(label) {
			label.html('&nbsp;').addClass('checked');
		}
	});
EOD;
joosDocument::instance()->add_js_code($validate_js_code);


$js_code = "
	var uploader = new qq.FileUploader({
		element: $('#file-uploader-avatar')[0],
		multiple: false,
		action:  _live_site + '/ajax/' ,
		button_label: 'Аватар',
		params: {
			option: 'users'
		},
		//debug: true,
		allowedExtensions: ['jpg', 'jpeg', 'png'],
		onComplete: function(id, fileName, responseJSON){
			var dateob = new Date();
			$('#edit_avatar_img').attr('src', _live_site + responseJSON.location + 'avatar_75x75.png' + '?'+dateob.getTime() );
		}
	});
";
joosDocument::instance()->add_js_code($js_code);
?>
<h3 class="g-blocktitle_orange">Редактирование профиля</h3>

<div id="user_edit_form" class="b-relative">

    <form action="/user/<?php echo $user->username ?>/edit" method="post" class="usereditform">

        <div class="f-block">
            <div class="f f-50 f-50_1">
                <label for="">Настоящее имя</label>
                <input type="text" name="realname" value="<?php echo $user->realname ?>" class="input-100"/>
            </div>

            <div class="f f-50 f-50_2">
                <div class="f f-50 f-50_1">
                    <label class="lbl_block" for="">День рождения</label>
                    <input type="date" name="birthdate" value="<?php echo $user_e->birthdate ?>" id="date"/>
                </div>
                <div class="f f-50 f-50_2">
                    <label class="lbl_block" for="">Пол</label>
					<?php echo forms::genderSelectList('gender', $user_e->gender) ?>
                </div>
            </div>
        </div>

        <div class="f-block odd">
            <div class="f  f-50 f-50_1">
                <label class="lbl_block" for="">Местоположение</label>
                <input type="text" name="location" value="<?php echo joosHtml::make_safe($user_e->location) ?>"
                       class="input-100"/>
            </div>
        </div>

		<?php $about = json_decode($user_e->about); ?>
        <div class="f-block">
            <div class="f">
                <label class="lbl_block" for="">О себе</label>
                <textarea name="about[about]" rows="5" cols="50"
                          class="input-100"><?php echo isset($about->about) ? $about->about : '' ?></textarea>
            </div>
        </div>

		<?php if ($user->gid == 9): ?>
	        <div class="f-block">
	            <div class="f">
	                <label class="lbl_block" for="">Анкета</label>
	                <textarea name="about[about2]" rows="5" cols="50"
	                          class="input-100"><?php echo isset($about->about2) ? $about->about2 : '' ?></textarea>
	            </div>
	        </div>
		<?php endif; ?>


        <div class="f-block odd">


            <div class="f f-50 f-50_2">
                <label class="lbl_block lbl_ganres" for="">Интересуюсь</label>
				<?php
				$all_interests = UsersExtra::get_interests();
				$interests_ch = array_chunk($all_interests, round(count($all_interests) / 2));

				$user_interests = $user_e->interests ? json_decode($user_e->interests) : array();
				?>
                <div class="f f-50 f-50_1">
					<?php
					foreach ($interests_ch[0] as $interest) {
						$checked = in_array($interest, $user_interests) ? ' checked="checked"' : '';
						echo '<input type="checkbox" name="interests[]" value="' . $interest . '"' . $checked . '><label class="opt" for="">' . $interest . '</label><br/>';
					}
					?>
                </div>
                <div class="f f-50 f-50_2">
					<?php
					foreach ($interests_ch[1] as $interest) {
						$checked = in_array($interest, $user_interests) ? ' checked="checked"' : '';
						echo '<input type="checkbox" name="interests[]" value="' . $interest . '"' . $checked . '><label class="opt" for="">' . $interest . '</label><br/>';
					}
					?>
                </div>
            </div>
        </div>

        <div class="f-block">
            <div class="f">
                <label class="lbl_block" for="">Контакты</label>

                <div id="contact_fields">
					<?php
					$contacts = ($user_e->contacts && $user_e->contacts != 'null') ? json_decode($user_e->contacts) : array('icq' => array(''));
					?>
					<?php
					$i = 0;
					foreach ($contacts as $type => $values) {
						foreach ($values as $val) {
							echo '<div class="f">';
							echo forms::dropdown_simple('contact_type', UsersExtra::get_contacts_types(), $type) . "\n";
							echo '<input type="text" value="' . $val . '" name="contacts[' . $type . '][]" />';
							echo '<span class="g-pseudolink" id="field_del">[x]</span>';
							echo '</div>';
							$i++;
						}
					}
					?>
                </div>

                <div class="f-tip"><span class="g-pseudolink" id="field_add">+ добавить</span></div>
            </div>
        </div>

        <div class="f-block odd">
            <div class="f f-50 f-50_1">
                <label class="lbl_block" for="">Email</label>
                <input type="email" name="email" value="<?php echo $user->email ?>" required="required"/>
            </div>
            <div class="f f-50 f-50_2">
                <label class="lbl_block" for="">Пароль</label>

                <div class="b-left b-50"><input type="password" name="password_old" value=""/></div>
                <input type="password" name="password_new" value=""/>

                <div class="f-tip b-left b-50">старый пароль</div>
                <div class="f-tip">новый пароль</div>
            </div>
        </div>

        <div class="f-block">
            <div class="f f-50 f-50_1">
                <span id="validation_errors" class="g-hidden">Вы заполнили не все поля формы</span>
                <button type="submit">Сохранить</button>
                <a class="button"
                   href="<?php echo joosRoute::href('user_view', array('id'=>$user->id,'username' => $user->username)) ?>">Отмена</a>
            </div>
        </div>

        <input name="id" type="hidden" value="<?php echo $user->id ?>"/>
        <input type="hidden" id="image_id" name="params[image_id]" value=""/>
    </form>

    <div class="f f-50 f-50_1" id="user_avatar_wrapper">
        <img id="edit_avatar_img" src="<?php echo Users::avatar($user->id, '75x75') ?>?<?php echo time() ?>" alt=""/>

        <div id="file-uploader-avatar"></div>
    </div>

</div>
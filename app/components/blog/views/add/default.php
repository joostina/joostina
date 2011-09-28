<?php
/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

$params   = json_decode( $item->params );

$image_id = '';
if ( isset( $params->image_id ) && $params->image_id != '' ) {
	$image_id = $params->image_id;
}

$validate_js_code = <<<EOD
        	var validator = $('#blog_add').validate({
		rules: {$validator->get_js_validator( 'rules' )},
		messages: {$validator->get_js_validator( 'messages' )},
		errorElement: 'div',
		errorClass: 'error',
		errorPlacement: function(error, element) {
			error.append('<em></em>');
			element.parent().append(error);
		},
		submitHandler: function(form) {
			ajax_blog();
		},
		success: function(label) {
			label.html('&nbsp;').addClass('checked');
		}
	});
EOD;
joosDocument::instance()->add_js_code( $validate_js_code );


$js_code = "
	var uploader = new qq.FileUploader({
		element: $('#file-uploader-blog')[0],
		multiple: false,
		action:  _live_site + '/ajax/' ,
		button_label: 'Загрузить картинку',
		params: {
			option: 'blog'
		},
		//debug: true,
		allowedExtensions: ['jpg', 'jpeg', 'png'],
		onComplete: function(id, fileName, responseJSON){
			var dateob = new Date();
			$('#blogimage').attr('src', _live_site + responseJSON.location + 'image_200x200.png' + '?'+dateob.getTime() );
			$('#image_id').val( responseJSON.file_id );
		}
	});
";
joosDocument::instance()->add_js_code( $js_code );

?>
<h3 class="g-blocktitle_orange">Супер-мега пост в блог</h3>

<?php if ( $item->_error_blog ): ?>
<div class="error">Есть ошибки</div>
<?php endif; ?>

<div id="blog_add_form">

	<form action="/blog/add" method="post" id="blog_add" class="form_validation">

		<div class="f-block">
			<div class="f f-50 f-50_1">
				<label for="title">Заголовок материала</label>
				<input type="text" name="title" id="title" value="<?php echo $item->title ?>" class="input-100"
				       required="required"/>

			</div>
		</div>
		<?php if ( joosCore::user()->gid == 8 || joosCore::user()->gid == 9 ): ?>
		<div class="f-block">
			<div class="f f-50 f-50_1">
				<label for="category_id">Категория</label>
				<?php echo $category_selector ?>
			</div>
		</div>
		<?php endif; ?>

		<div class="f-block odd">
			<div class="f">
				<label class="lbl_block" for="fulltext">Текст статьи</label>
				<textarea name="fulltext" id="fulltext" rows="15" cols="50" class="input-100"
				          required="required"><?php echo $item->fulltext ?></textarea>
			</div>
		</div>
		<div class="f-block" id="form_actions">
			<div class="f f-50 f-50_1">
				<input type="hidden" name="state" value="1"/>
				<input type="checkbox" name="state" value="0"
				       id="state" <?php echo ( $item->id && $item->state == 0 ) ? 'checked="checked"' : '' ?> /><label
				class="opt" for="state">Это черновик</label>
			</div>
			<div class="f f-50 f-50_2">
				<button type="submit" id="blog_add_button">Сохранить</button>
				<button>Отмена</button>
			</div>
			<span id="validation_errors" class="g-hidden">Вы заполнили не все поля формы</span>
		</div>
		<input type="hidden" id="image_id" name="params[image_id]" value="<?php echo $image_id ?>"/>
		<input type="hidden" id="id" name="id" value="<?php echo $item->id ?>"/>
	</form>

	<div class="f-block">
		<div class="f f-50 f-50_1">
			<?php $image = Blog::get_image( $item , '200x200' , array ( 'id' => 'blogimage' ) ); ?>
			<?php echo $image != false ? $image : Blog::get_image_default( array ( 'id' => 'blogimage' ) ) ?>
			<br/>

			<div id="file-uploader-blog"></div>
		</div>
	</div>
</div>
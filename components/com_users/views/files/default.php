<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined( '_VALID_MOS' ) or die();

mosMainFrame::addLib('files');

$is_owner = false;
if($my->id && $my->id == $user->id){ $is_owner = true;}
?>

<div class="page page_my_files">

    <?php require_once JPATH_BASE.'/components/com_users/views/navigation/profile.php'; ?>
    
    <?php if($is_owner) :?>
        <?php require_once JPATH_BASE.'/components/com_users/views/uploadform/default.php';?>
    <?php endif;?>

    <?php if(!$files):?>
        <div class="notice"></div>
    <?php else:?>
        <table width="100%" class="my_files">
        	<tr>        		
				<th>Файл</th>
				<th width="90">Тип файла</th>
				<th width="70">Размер</th>
				<th width="80">Загружен</th>
			</tr>
            <?php foreach( $files as $file ):// файлы ?>
            <?php $filename = JPATH_SITE_IMAGES .'/attachments/'.$file->file_mime.'/'.File::makefilename( $file->id ).'/'.$file->file_name; ?>
            <tr>
            	
                <td>
                    <a href="<?php echo $filename  ?>"><?php echo $file->file_name ?></a>
                    <br />
                    <input style="width: 90%" type="text" value="<?php echo $filename ?>" />
                </td>
                <td><div class="overflow"><?php echo $file->file_mime ?></div></td>
                <td><?php echo File::formatBytes($file->file_size, 'Mb') ?> mb.</td>
                <td><?php echo $file->created_at ?></td>
            </tr>
            <?php endforeach;// файлы ?>
        </table>
    <?php endif;?>

</div>
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

/**
 * @package Joostina
 * @subpackage Languages
 */
class HTML_languages {

    function showLanguages($cur_lang,&$rows,&$pageNav,$option) {
        global $my;
        $mainframe = mosMainFrame::getInstance();
        $cur_file_icons_path = JPATH_SITE.'/'.JADMIN_BASE.'/templates/'.JTEMPLATE.'/images/ico';
        ?>
<form action="index2.php" method="post" name="adminForm">
    <table class="adminheading">
        <tr>
            <th class="langmanager">
                        <?php echo _LANGUAGE_PACKS?> <small><small>[ <?php echo _SITE?> ]</small></small>
            </th>
        </tr>
    </table>

    <table class="adminlist">
        <tr>
            <th width="20">
			#
            </th>
            <th width="30">
                &nbsp;
            </th>
            <th width="25%" class="title">
                        <?php echo _E_LANGUAGE?>
            </th>
            <th width="5%">
                        <?php echo _USED_ON?>
            </th>
            <th width="10%">
                        <?php echo _VERSION?>
            </th>
            <th width="10%">
                        <?php echo _DATE?>
            </th>
            <th width="20%">
                        <?php echo _AUTHOR?>
            </th>
            <th width="25%">
			E-mail
            </th>
        </tr>
                <?php
                $k = 0;
                for($i = 0,$n = count($rows); $i < $n; $i++) {
                    $row = &$rows[$i];
                    ?>
        <tr class="<?php echo "row$k"; ?>">
            <td width="20"><?php echo $pageNav->rowNumber($i); ?></td>
            <td width="20">
                <input type="radio" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $row->language; ?>" onClick="isChecked(this.checked);" />
            </td>
            <td width="25%">
                <a href="#edit" onclick="return listItemTask('cb<?php echo $i; ?>','edit_source')"><?php echo $row->name; ?></a></td>
            <td width="5%" align="center">
                            <?php
                            if($row->published == 1) { ?>
                <img src="<?php echo $cur_file_icons_path;?>/tick.png" alt="<?php echo _PUBLISHED?>"/>
                                <?php
                            } else {
                                ?>
                &nbsp;
                                <?php
                            }
                            ?>
            </td>
            <td align=center>
                            <?php echo $row->version; ?>
            </td>
            <td align=center>
                            <?php echo $row->creationdate; ?>
            </td>
            <td align=center>
                            <?php echo $row->author; ?>
            </td>
            <td align=center>
                            <?php echo $row->authorEmail; ?>
            </td>
        </tr>
                    <?php
                }
                ?>
    </table>
            <?php echo $pageNav->getListFooter(); ?>

    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="hidemainmenu" value="0" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
        <?php
    }

    function editLanguageSource($language,&$content,$option) {
        $language_path = JPATH_BASE."/language/".$language."/system.php";
        ?>
<form action="index2.php" method="post" name="adminForm">
    <table cellpadding="1" cellspacing="1" border="0" width="100%">
        <tr>
            <td width="270">
                <table class="adminheading">
                    <tr>
                        <th class="langmanager"><?php echo _LANGUAGE_EDITOR?></th>
                    </tr>
                </table>
            </td>
            <td width="240">
                <span class="componentheading"> system.php :<b><?php echo is_writable($language_path)?'<font color="green"> '._WRITEABLE.'</font>':'<font color="red"> '._UNWRITEABLE.'</font>' ?></b>
                </span>
            </td>
                    <?php
                    if(mosIsChmodable($language_path)) {
                        if(is_writable($language_path)) {
                            ?>
            <td>
                <input type="checkbox" id="disable_write" name="disable_write" value="1"/>
                <label for="disable_write"><?php echo _MAKE_UNWRITEABLE_AFTER_SAVING?></label>
            </td>
                            <?php
                        } else {
                            ?>
            <td>
                <input type="checkbox" id="enable_write" name="enable_write" value="1"/>
                <label for="enable_write"><?php echo _IGNORE_WRITE_PROTECTION_WHEN_SAVE?></label>
            </td>
                            <?php
                        } // if
                    } // if

                    ?>
        </tr>
    </table>
    <table class="adminform">
        <tr><th><?php echo $language_path; ?></th></tr>
        <tr><td><textarea style="width:100%" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
    </table>
    <input type="hidden" name="language" value="<?php echo $language; ?>" />
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
        <?php
    }
}
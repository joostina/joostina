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
 * Display Component item link
 * @package Joostina
 * @subpackage Menus
 */
class component_item_link_menu_html {

    function edit(&$menu,&$lists,&$params,$option) {
        
        ?>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
<script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
        var form = document.adminForm;
        if ( pressbutton == 'cancel' ) {
            submitform( pressbutton );
            return;
        }

        // do field validation
        if ( $.trim(form.name.value) == "" ){
            alert( "<?php echo _LINK_MUST_HAVE_NAME?>" );
        } else if ( trim( form.link.value ) == "" ){
            alert( "<?php echo _CHOOSE_COMPONENT_FOR_LINK?>" );
        } else {
            submitform( pressbutton );
        }
    }
</script>
<form action="index2.php" method="post" name="adminForm">
    <table class="adminheading">
        <tr>
            <th class="menus">
                        <?php echo $menu->id?_EDITING.' -':_CREATION.' -'; ?> <?php echo _MENU_ITEM_COMPONENT_LINK?>
            </th>
        </tr>
    </table>
    <table width="100%">
        <tr valign="top">
            <td width="60%">
                <table class="adminform">
                    <tr>
                        <th colspan="2"><?php echo _DETAILS?></th>
                    </tr>
                    <tr>
                        <td width="10%" align="right"><?php echo _NAME?>:</td>
                        <td width="80%">
                            <input class="inputbox" type="text" name="name" id="name" size="50" maxlength="100" value="<?php echo htmlspecialchars($menu->name,ENT_QUOTES); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td width="10%" align="right" valign="top"><?php echo _LINK_TITLE?>:</td>
                        <td width="80%">
                            <input class="inputbox" type="text" name="page_title" size="50" maxlength="100" value="<?php echo htmlspecialchars($menu->page_title,ENT_QUOTES,'UTF-8'); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td width="10%" align="right" valign="top"><?php echo _LINK_COMPONENT?>:</td>
                        <td width="80%"><?php echo $lists['components']; ?></td>
                    </tr>
                    <tr>
                        <td width="10%" align="right">URL:</td>
                        <td width="80%"><?php echo ampReplace($lists['link']); ?></td>
                    </tr>
                    <tr>
                        <td width="10%" align="right" valign="top"><?php echo _LINK_TARGET?>
                        </td>
                        <td width="80%">
                                    <?php echo $lists['target']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                                    <?php echo _PARENT_MENU_ITEM?>:
                        </td>
                        <td>
                                    <?php echo $lists['parent']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">
                                    <?php echo _ORDER_DROPDOWN?>:
                        </td>
                        <td>
                                    <?php echo $lists['ordering']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right">
                                    <?php echo _ACCESS?>:
                        </td>
                        <td>
                                    <?php echo $lists['access']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="right"><?php echo _PUBLISHED?>:</td>
                        <td><?php echo $lists['published']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td width="40%">
                <table class="adminform">
                    <tr>
                        <th><?php echo _PARAMETERS?></th>
                    </tr>
                    <tr>
                        <td><?php echo $params->render(); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="id" value="<?php echo $menu->id; ?>" />
    <input type="hidden" name="menutype" value="<?php echo $menu->menutype; ?>" />
    <input type="hidden" name="type" value="<?php echo $menu->type; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="hidemainmenu" value="0" />
    <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
</form>
        <?php
    }
}
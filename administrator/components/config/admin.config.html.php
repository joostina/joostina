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

/**
 * @package Joostina
 * @subpackage Config
 */
class HTML_config {

	public static function showconfig(&$row,&$lists,$option) {
		global $mosConfig_session_type,$mainframe;


		mosMainFrame::addClass('mosTabs');
		$tabs = new mosTabs(1,1);

		?>
<script type="text/javascript">
	<!--
	function saveFilePerms() {
		var f = document.adminForm;
		if (f.filePermsMode0.checked){
			f.config_fileperms.value = '';
		}else {
			var perms = 0;
			if (f.filePermsUserRead.checked) perms += 400;
			if (f.filePermsUserWrite.checked) perms += 200;
			if (f.filePermsUserExecute.checked) perms += 100;
			if (f.filePermsGroupRead.checked) perms += 40;
			if (f.filePermsGroupWrite.checked) perms += 20;
			if (f.filePermsGroupExecute.checked) perms += 10;
			if (f.filePermsWorldRead.checked) perms += 4;
			if (f.filePermsWorldWrite.checked) perms += 2;
			if (f.filePermsWorldExecute.checked) perms += 1;
			f.config_fileperms.value = '0'+''+perms;
		}
	}
	function changeFilePermsMode(mode) {
		if(document.getElementById) {
			switch (mode) {
				case 0:
					SRAX.get('filePermsValue').style.display = 'none';
					SRAX.get('filePermsTooltip').style.display = '';
					SRAX.get('filePermsFlags').style.display = 'none';
					break;
				default:
					SRAX.get('filePermsValue').style.display = '';
					SRAX.get('filePermsTooltip').style.display = 'none';
					SRAX.get('filePermsFlags').style.display = '';
			} // switch
		} // if
		saveFilePerms();
	}
	function saveDirPerms()  {
		var f = document.adminForm;
		if (f.dirPermsMode0.checked)
			f.config_dirperms.value = '';
		else {
			var perms = 0;
			if (f.dirPermsUserRead.checked) perms += 400;
			if (f.dirPermsUserWrite.checked) perms += 200;
			if (f.dirPermsUserSearch.checked) perms += 100;
			if (f.dirPermsGroupRead.checked) perms += 40;
			if (f.dirPermsGroupWrite.checked) perms += 20;
			if (f.dirPermsGroupSearch.checked) perms += 10;
			if (f.dirPermsWorldRead.checked) perms += 4;
			if (f.dirPermsWorldWrite.checked) perms += 2;
			if (f.dirPermsWorldSearch.checked) perms += 1;
			f.config_dirperms.value = '0'+''+perms;
		}
	}
	function changeDirPermsMode(mode)   {
		if(document.getElementById) {
			switch (mode) {
				case 0:
					SRAX.get('dirPermsValue').style.display = 'none';
					SRAX.get('dirPermsTooltip').style.display = '';
					SRAX.get('dirPermsFlags').style.display = 'none';
					break;
				default:
					SRAX.get('dirPermsValue').style.display = '';
					SRAX.get('dirPermsTooltip').style.display = 'none';
					SRAX.get('dirPermsFlags').style.display = '';
			} // switch
		} // if
		saveDirPerms();
	}
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (form.config_session_type.value != <?php echo $row->config_session_type; ?> ){
			if ( confirm('<?php echo _DO_YOU_REALLY_WANT_DEL_AUTENT_METHOD?>') ) {
				submitform( pressbutton );
			} else {
				return;
			}
		} else {
			submitform( pressbutton );
		}
	}
	function ch_apply(){
		SRAX.get('tb-apply').className='tb-load';
		saveFilePerms();
		saveDirPerms();
		dax({
			url: 'ajax.index.php?option=com_config&task=apply',
			id:'publ-1',
			method:'post',
			form: 'adminForm',
			callback:
				function(resp){
				mess_cool(resp.responseText);
				SRAX.get('tb-apply').className='tb-apply';
			}});
	}
	//-->
</script>
<div style="text-align:left;">
	<form action="index2.php" method="post" name="adminForm" id="adminForm">
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<table cellpadding="1" cellspacing="1" border="0" width="100%">
			<tr>
				<td width="70%"><table class="adminheading"><tr><th class="config"><?php echo _GLOBAL_CONFIG?></th></tr></table></td>
						<?php
						if(mosIsChmodable('../configuration.php')) {
							if(is_writable('../configuration.php')) {
								?>
				<td class="jtd_nowrap">
					<input type="checkbox" id="disable_write" name="disable_write" value="1"/>
					<label for="disable_write"><?php echo _PROTECT_AFTER_SAVE?></label>
				</td>
								<?php
							} else {
								?>
				<td class="jtd_nowrap">
					<input type="checkbox" id="enable_write" name="enable_write" value="1"/>
					<label for="enable_write"><?php echo _IGNORE_PROTECTION_WHEN_SAVE?></label>
				</td>
								<?php
							} // if
						} // if

						?>
			</tr>
		</table>
		<div class="message"><?php echo _CONFIG_SAVING?>:
					<?php echo is_writable('../configuration.php')?' <b><font color="green">'._AVAILABLE_CHECK_RIGHTS.'</font></b>':' <b><font color="red">'._NOT_AVAILABLE_CHECK_RIGHTS.'</font></b>' ?>
		</div>
		<br />
				<?php
				$tabs->startPane("configPane");
				$tabs->startTab(_SITE,"site-page");
				?>
		<table width="100%">
			<tr>
				<td>
					<table class="sub_adminform">
						<tr>
							<td><?php echo _SITE_NAME?>:</td>
							<td><input class="text_area" style="width:98%;" type="text" name="config_sitename" size="50" value="<?php echo $row->config_sitename; ?>"/></td>
						</tr>
						<tr>
							<td width="250"><?php echo _SITE_OFFLINE?>:</td>
							<td><?php echo $lists['offline']; ?></td>
						</tr>
						<tr>
							<td valign="top"><?php echo mosToolTip(_SITE_OFFLINE_MESSAGE2,'','','',_SITE_OFFLINE_MESSAGE);?>:</td>
							<td><textarea class="text_area" cols="60" rows="2" style="width:98%; height:50px" name="config_offline_message"><?php echo $row->config_offline_message; ?></textarea></td>
						</tr>
						<tr>
							<td valign="top"><?php echo mosToolTip(_SYSTEM_ERROR_MESSAGE2,'','','',_SYSTEM_ERROR_MESSAGE); ?>:</td>
							<td><textarea class="text_area" cols="60" rows="2" style="width:98%; height:50px" name="config_error_message"><?php echo $row->config_error_message; ?></textarea></td>
						</tr>
					</table>
				</td>
				<td valign="top">
					<table class="sub_adminform">
						<tr>
							<td><?php echo mosToolTip(_ENABLE_USER_REGISTRATION2,'','','',_ENABLE_USER_REGISTRATION)?>:</td>
							<td><?php echo $lists['allowUserRegistration']; ?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_USER_PARAMS2,'','','',_USER_PARAMS)?>:</td>
							<td><?php echo $lists['frontend_userparams']; ?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_LIST_LIMIT2,'','','',_LIST_LIMIT)?>:</td>
							<td><?php echo $lists['list_limit'];?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

				<?php
				$tabs->endTab();
				$tabs->startTab(_FRONTPAGE,"front-page");
				?>
		<table width="100%">
			<tr>
				<td>
					<table class="sub_adminform">
						<tr>
							<td width="300"><?php echo _COM_CONFIG_SITE_LANG?>:</td>
							<td><?php echo $lists['lang']; ?></td>
						</tr>
						<td><?php echo mosToolTip(_CUSTOM_PRINT2,'','','',_CUSTOM_PRINT)?>:</td>
						<td><?php echo $lists['config_custom_print'];?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_DATE_FORMAT2,'','','',_DATE_FORMAT_TXT)?>:</td>
				<td><input class="text_area" type="text" name="config_form_date" size="20" value="<?php echo $row->config_form_date; ?>"/><?php echo $lists['form_date_help'];?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_DATE_FORMAT_FULL2,'','','',_DATE_FORMAT_FULL)?>:</td>
				<td><input class="text_area" type="text" name="config_form_date_full" size="20" value="<?php echo $row->config_form_date_full; ?>"/><?php echo $lists['form_date_full_help'];?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_USE_TEMPLATE2,'','','',_USE_TEMPLATE)?>:</td>
				<td><?php echo $lists['one_template'];?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_FAVICON_IMAGE2,'','','',_FAVICON_IMAGE)?>:</td>
				<td><input class="text_area" type="text" name="config_favicon" size="20" value="<?php echo $row->config_favicon; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_DISABLE_FAVICON2,'','','',_DISABLE_FAVICON)?>:</td>
				<td><?php echo $lists['config_disable_favicon'];?></td>
			</tr>
		</table>
		</td>
		<td valign="top">
			<table class="sub_adminform">
				<tr>
					<td><?php echo mosToolTip(_SITE_AUTH2,'','','',_SITE_AUTH)?>:</td>
					<td><?php echo $lists['frontend_login'];?></td>
				</tr>
				<tr>
					<td><?php echo mosToolTip(_FRONT_SESSION_TIME2,'','','',_FRONT_SESSION_TIME)?>:</td>
					<td><input class="text_area" type="text" name="config_lifetime" size="10" value="<?php echo $row->config_lifetime; ?>"/> <?php echo _SECONDS?></td>
				</tr>
				<tr>
					<td><?php echo mosToolTip(_DISABLE_FRONT_SESSIONS2,'','','',_DISABLE_FRONT_SESSIONS)?></td>
					<td><?php echo $lists['session_front'];?>
					</td>
				</tr>
				<tr>
					<td><?php echo mosToolTip(_COUNT_GENERATION_TIME2,'','','',_COUNT_GENERATION_TIME)?>:</td>
					<td><?php echo $lists['config_time_generate'];?></td>
				</tr>
				<tr>
					<td><?php echo mosToolTip(_ENABLE_GZIP2,'','','',_ENABLE_GZIP)?>:</td>
					<td><?php echo $lists['gzip'];?></td>
				</tr>
				<tr>
					<td><?php echo mosToolTip(_IS_SITE_DEBUG2,'','','',_IS_SITE_DEBUG)?>:</td>
					<td><?php echo $lists['debug']; ?></td>
				</tr>
				<tr>
					<td><?php echo mosToolTip(_EXTENDED_DEBUG2,'','','',_EXTENDED_DEBUG)?>:</td>
					<td><?php echo $lists['config_front_debug'];  ?></td>
				</tr>
				<tr>
					<td><?php echo mosToolTip(_DISABLE_TPREVIEW_INFO,'','','',_DISABLE_TPREVIEW)?>:</td>
					<td><?php echo $lists['tpreview']; ?></td>
				</tr>
			</table>
		</td>
		</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(_CONTROL_PANEL,"back-page");
				?>
		<table width="100%">
			<tr>
				<td>
					<table class="sub_adminform">
						<tr>
							<td width="300"><?php echo mosToolTip(_DISABLE_ADMIN_SESS_DEL2,'','','',_DISABLE_ADMIN_SESS_DEL)?>:</td>
							<td><?php echo $lists['config_admin_autologout']; ?></td>
						</tr>
						<tr>
							<td width="300"><?php echo mosToolTip(_ADMIN_SECURE_CODE_HELP,'','','',_ENABLE_ADMIN_SECURE_CODE)?>:</td>
							<td><?php echo $lists['config_enable_admin_secure_code'];?></td>
						</tr>
						<tr>
							<td><?php echo _ADMIN_SECURE_CODE?>:</td>
							<td><input class="text_area" type="text" name="config_admin_secure_code" size="60" value="<?php echo $row->config_admin_secure_code; ?>"/></td>
						</tr>
						<tr>
							<td width="300"><?php echo mosToolTip(_ADMIN_SECURE_CODE_OPTION,'','','',_ADMIN_SECURE_CODE_REDIRECT_OPTIONS)?>:</td>
							<td><?php echo $lists['config_admin_redirect_options'];?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_ADMIN_SECURE_CODE_REDIRECT_PATH,'','','',_ADMIN_SECURE_CODE_REDIRECT_PATH)?>:</td>
							<td><input class="text_area" type="text" name="config_admin_redirect_path" size="60" value="<?php echo $row->config_admin_redirect_path; ?>"/></td>
						</tr>
						<tr>
							<td><?php echo _COM_CONFIG_COUNT_FOR_USER_BLOCK?>:</td>
							<td><input class="text_area" type="text" name="config_count_for_user_block" size="10" value="<?php echo $row->config_count_for_user_block; ?>"/></td>
						</tr>
					</table>
				</td><td valign="top">
					<table class="sub_adminform">
						<tr>
							<td><?php echo mosToolTip(_ADMIN_SESS_TIME2,'','','',_ADMIN_SESS_TIME)?>:</td>
							<td>
								<input class="text_area" type="text" name="config_session_life_admin" size="10" value="<?php echo $row->config_session_life_admin; ?>"/>&nbsp;<?php echo _SECONDS?>&nbsp;
										<?php echo mosWarning(_ADMIN_SESS_TIME2); ?>
							</td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_SAVE_LAST_PAGE2,'','','',_SAVE_LAST_PAGE)?>:</td>
							<td><?php echo $lists['admin_expired'];?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_USE_TEMPLATE2,'','','',_USE_TEMPLATE)?>:</td>
							<td><?php echo $lists['config_admin_template']; ?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_COM_CONFIG_COMPONENTS_ACCESS_HELP,'','','',_COM_CONFIG_COMPONENTS_ACCESS)?>:</td>
							<td><?php echo $lists['components_access']; ?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(_LOCALE,"Locale-page");
				?>
		<table width="100%">
			<tr>
				<td width="185"><?php echo mosToolTip(_TIME_OFFSET2." ".mosCurrentDate(_DATE_FORMAT_LC2),'','','',_TIME_OFFSET)?>:</td>
				<td><?php echo $lists['offset'];?></td>
			</tr>
			<tr>
				<td width="185"><?php echo mosToolTip(_LINK_TITLES2,'','','',_TIME_DIFF)?>:</td>
				<td><input class="text_area" type="text" name="config_offset" size="15" value="<?php echo $row->config_offset; ?>" disabled="disabled"/></td>
			</tr>
			<tr>
				<td width="185"><?php echo mosToolTip(_CURR_DATE_TIME_RSS.": ".mosCurrentDate(_DATE_FORMAT_LC2),'','','',_TIME_DIFF2)?>:</td>
				<td><?php echo $lists['feed_timeoffset']; ?></td>
			</tr>
			<tr>
				<td width="185"><?php echo mosToolTip(_COUNTRY_LOCALE2,'','','',_COUNTRY_LOCALE)?>:</td>
				<td><?php echo $lists['locale']; ?></td>
			</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(_DATABASE,"db-page");
				?>
		<table width="100%">
			<tr>
				<td width="185"><?php echo _DB_HOST?>:</td>
				<td><input class="text_area" type="text" name="config_host" size="25" value="<?php echo $row->config_host; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo _DB_USER?>:</td>
				<td><input class="text_area" type="text" name="config_user" size="25" value="<?php echo $row->config_user; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo _DB_NAME?>:</td>
				<td><input class="text_area" type="text" name="config_db" size="25" value="<?php echo $row->config_db; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo _DB_PREFIX?>:</td>
				<td><input class="text_area" type="text" name="config_dbprefix" size="10" value="<?php echo $row->config_dbprefix; ?>"/>&nbsp;<?php echo mosWarning(_DB_PREFIX2); ?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_EVERYDAY_OPTIMIZATION2,'','','',_EVERYDAY_OPTIMIZATION)?>:</td>
				<td><?php echo $lists['optimizetables'];?></td>
			</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(_SERVER,"server-page");
				?>
		<table width="100%">
			<tr>
				<td>
					<table class="sub_adminform">
						<tr>
							<td><?php echo _COM_CONFIG_SYTE_URL?>:</td>
							<td><strong><?php echo $row->config_live_site; ?></strong></td>
						</tr>
						<tr>
							<td width="185"><?php echo _ABS_PATH?>:</td>
							<td width="450"><strong><?php echo $row->config_absolute_path; ?></strong></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_MEDIA_ROOT2,'','','',_MEDIA_ROOT)?>:</td>
							<td><input class="text_area" type="text" name="config_media_dir" size="50" value="<?php echo $row->config_media_dir; ?>"/></td>
						</tr>
						<tr>
							<td><?php echo _SECRET_WORD?>:</td>
							<td><strong><?php echo $row->config_secret; ?></strong></td>
						</tr>
						<tr>
							<td><?php echo _SESSION_TYPE?>:</td>
							<td>
										<?php echo $lists['session_type']; ?>
								&nbsp;
										<?php echo mosWarning(_SESSION_TYPE2); ?>
							</td>
						</tr>
						<tr>
							<td><?php echo _ERROR_REPORTING?>:</td>
							<td><?php echo $lists['error_reporting']; ?></td>
						</tr>
					</table>
				</td><td valign="top">
					<table class="sub_adminform">
						<tr>
									<?php
									$mode = 0;
									$flags = 0644;
									if($row->config_fileperms != '') {
										$mode = 1;
										$flags = octdec($row->config_fileperms);
									} // if

									?>
							<td valign="top"><?php echo _FILE_MODE?>:</td>
							<td>
								<fieldset><legend><?php echo _FILE_MODE2?></legend>
									<table cellpadding="1" cellspacing="1" border="0">
										<tr>
											<td><input type="radio" id="filePermsMode0" name="filePermsMode" value="0" onclick="changeFilePermsMode(0)"<?php if(!$mode) echo ' checked="checked"'; ?>/></td>
											<td><label for="filePermsMode0"><?php echo _FILE_MODE3?></label></td>
										</tr>
										<tr>
											<td><input type="radio" id="filePermsMode1" name="filePermsMode" value="1" onclick="changeFilePermsMode(1)"
															   <?php if($mode) echo ' checked="checked"'; ?>/></td>
											<td>
												<label for="filePermsMode1"><?php echo _FILE_MODE4?></label>
												<span id="filePermsValue"<?php if(!$mode) echo ' style="display:none"'; ?>>
													<input class="text_area" type="text" readonly="readonly" name="config_fileperms" size="4" value="<?php echo $row->config_fileperms; ?>"/>
												</span>
												<span id="filePermsTooltip"<?php if($mode) echo ' style="display:none"'; ?>>
													&nbsp;<?php echo mosToolTip(_FILE_MODE5); ?>
												</span>
											</td>
										</tr>
										<tr id="filePermsFlags"<?php if(!$mode) echo ' style="display:none"'; ?>>
											<td>&nbsp;</td>
											<td>
												<table cellpadding="0" cellspacing="1" border="0">
													<tr>
														<td style="padding:0px"><?php echo _OWNER?>:</td>
														<td style="padding:0px"><input type="checkbox" id="filePermsUserRead" name="filePermsUserRead" value="1" onclick="saveFilePerms()"<?php if($flags &0400) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="filePermsUserRead"><?php echo _O_READ?></label></td>
														<td style="padding:0px"><input type="checkbox" id="filePermsUserWrite" name="filePermsUserWrite" value="1" onclick="saveFilePerms()"<?php if($flags &0200) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="filePermsUserWrite"><?php echo _O_WRITE?></label></td>
														<td style="padding:0px"><input type="checkbox" id="filePermsUserExecute" name="filePermsUserExecute" value="1" onclick="saveFilePerms()"<?php if($flags &0100) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px" colspan="3"><label for="filePermsUserExecute"><?php echo _O_EXEC?></label></td>
													</tr>
													<tr>
														<td style="padding:0px"><?php echo _GROUP?>:</td>
														<td style="padding:0px"><input type="checkbox" id="filePermsGroupRead" name="filePermsGroupRead" value="1" onclick="saveFilePerms()"<?php if($flags &040) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="filePermsGroupRead"><?php echo _O_READ?></label></td>
														<td style="padding:0px"><input type="checkbox" id="filePermsGroupWrite" name="filePermsGroupWrite" value="1" onclick="saveFilePerms()"<?php if($flags &020) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="filePermsGroupWrite"><?php echo _O_WRITE?></label></td>
														<td style="padding:0px"><input type="checkbox" id="filePermsGroupExecute" name="filePermsGroupExecute" value="1" onclick="saveFilePerms()"<?php if($flags &010) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px" width="70"><label for="filePermsGroupExecute"><?php echo _O_EXEC?></label></td>
														<td><input type="checkbox" id="applyFilePerms" name="applyFilePerms" value="1"/></td>
														<td class="jtd_nowrap">
															<label for="applyFilePerms">
																		<?php echo _APPLY_TO_FILES?>
																&nbsp;
																		<?php
																		echo mosWarning(_APPLY_TO_FILES2); ?>
															</label>
														</td>
													</tr>
													<tr>
														<td style="padding:0px"><?php echo _ALL?>:</td>
														<td style="padding:0px"><input type="checkbox" id="filePermsWorldRead" name="filePermsWorldRead" value="1" onclick="saveFilePerms()"<?php if($flags &04) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="filePermsWorldRead"><?php echo _O_READ?></label></td>
														<td style="padding:0px"><input type="checkbox" id="filePermsWorldWrite" name="filePermsWorldWrite" value="1" onclick="saveFilePerms()"<?php if($flags &02) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="filePermsWorldWrite"><?php echo _O_WRITE?></label></td>
														<td style="padding:0px"><input type="checkbox" id="filePermsWorldExecute" name="filePermsWorldExecute" value="1" onclick="saveFilePerms()"<?php if($flags &01) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px" colspan="4"><label for="filePermsWorldExecute"><?php echo _O_EXEC?></label></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
						<tr>
									<?php
									$mode = 0;
									$flags = 0755;
									if($row->config_dirperms != '') {
										$mode = 1;
										$flags = octdec($row->config_dirperms);
									} // if

									?>
							<td valign="top"><?php echo _DIR_CREATION?>:</td>
							<td>
								<fieldset><legend><?php echo _DIR_CREATION2?></legend>
									<table cellpadding="1" cellspacing="1" border="0">
										<tr>
											<td><input type="radio" id="dirPermsMode0" name="dirPermsMode" value="0" onclick="changeDirPermsMode(0)"<?php if(!$mode) echo ' checked="checked"'; ?>/></td>
											<td><label for="dirPermsMode0"><?php echo _DIR_CREATION3?></label></td>
										</tr>
										<tr>
											<td><input type="radio" id="dirPermsMode1" name="dirPermsMode" value="1" onclick="changeDirPermsMode(1)"<?php if($mode) echo ' checked="checked"'; ?>/></td>
											<td>
												<label for="dirPermsMode1"><?php echo _DIR_CREATION4?></label>
												<span id="dirPermsValue"<?php if(!$mode) echo ' style="display:none"'; ?>>
															<?php echo _O_AS?>: <input class="text_area" type="text" readonly="readonly" name="config_dirperms" size="4" value="<?php echo $row->config_dirperms; ?>"/>
												</span>
												<span id="dirPermsTooltip"<?php if($mode) echo ' style="display:none"'; ?>>
													&nbsp;<?php echo mosToolTip(_DIR_CREATION5); ?>
												</span>
											</td>
										</tr>
										<tr id="dirPermsFlags"<?php if(!$mode) echo ' style="display:none"'; ?>>
											<td>&nbsp;</td>
											<td>
												<table cellpadding="1" cellspacing="0" border="0">
													<tr>
														<td style="padding:0px"><?php echo _OWNER?>:</td>
														<td style="padding:0px"><input type="checkbox" id="dirPermsUserRead" name="dirPermsUserRead" value="1" onclick="saveDirPerms()"<?php if($flags &0400) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="dirPermsUserRead"><?php echo _O_READ?></label></td>
														<td style="padding:0px"><input type="checkbox" id="dirPermsUserWrite" name="dirPermsUserWrite" value="1" onclick="saveDirPerms()"<?php if($flags &0200) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="dirPermsUserWrite"><?php echo _O_WRITE?></label></td>
														<td style="padding:0px"><input type="checkbox" id="dirPermsUserSearch" name="dirPermsUserSearch" value="1" onclick="saveDirPerms()"<?php if($flags &0100) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px" colspan="3"><label for="dirPermsUserSearch"><?php echo _O_SEARCH?></label></td>
													</tr>
													<tr>
														<td style="padding:0px"><?php echo _GROUP?>:</td>
														<td style="padding:0px"><input type="checkbox" id="dirPermsGroupRead" name="dirPermsGroupRead" value="1" onclick="saveDirPerms()"<?php if($flags &040) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="dirPermsGroupRead"><?php echo _O_READ?></label></td>
														<td style="padding:0px"><input type="checkbox" id="dirPermsGroupWrite" name="dirPermsGroupWrite" value="1" onclick="saveDirPerms()"<?php if($flags &020) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="dirPermsGroupWrite"><?php echo _O_WRITE?></label></td>
														<td style="padding:0px"><input type="checkbox" id="dirPermsGroupSearch" name="dirPermsGroupSearch" value="1" onclick="saveDirPerms()"<?php if($flags &010) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px" width="70"><label for="dirPermsGroupSearch"><?php echo _O_SEARCH?></label></td>
														<td><input type="checkbox" id="applyDirPerms" name="applyDirPerms" value="1"/></td>
														<td class="jtd_nowrap">
															<label for="applyDirPerms">
																		<?php echo _APPLY_TO_DIRS?>&nbsp;
																		<?php echo mosWarning(_APPLY_TO_DIRS2); ?>
															</label>
														</td>
													</tr>
													<tr>
														<td style="padding:0px"><?php echo _ALL?>:</td>
														<td style="padding:0px"><input type="checkbox" id="dirPermsWorldRead" name="dirPermsWorldRead" value="1" onclick="saveDirPerms()"<?php if($flags &04) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="dirPermsWorldRead"><?php echo _O_READ?></label></td>
														<td style="padding:0px"><input type="checkbox" id="dirPermsWorldWrite" name="dirPermsWorldWrite" value="1" onclick="saveDirPerms()"<?php if($flags &02) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px"><label for="dirPermsWorldWrite"><?php echo _O_WRITE?></label></td>
														<td style="padding:0px"><input type="checkbox" id="dirPermsWorldSearch" name="dirPermsWorldSearch" value="1" onclick="saveDirPerms()"<?php if($flags &01) echo ' checked="checked"'; ?>/></td>
														<td style="padding:0px" colspan="3"><label for="dirPermsWorldSearch"><?php echo _O_SEARCH?></label></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</fieldset>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(_METADATA,"metadata-page");
				?>
		<table width="100%">
			<tr>
				<td>
					<table class="sub_adminform">
						<tr>
							<td valign="top" width="250"><?php echo mosToolTip(_SITE_DESC2,'','','',_SITE_DESC)?>:</td>
							<td><textarea class="text_area" cols="50" rows="3" style="width:85%; height:60px" name="config_MetaDesc"><?php echo $row->config_MetaDesc; ?></textarea></td>
						</tr>
						<tr>
							<td valign="top"><?php echo _SITE_KEYWORDS?>:</td>
							<td><textarea class="text_area" cols="50" rows="3" style="width:85%; height:60px" name="config_MetaKeys"><?php echo $row->config_MetaKeys; ?></textarea></td>
						</tr>
					</table>
				</td><td valign="top">
					<table class="sub_adminform">
						<tr>
							<td><?php echo mosToolTip(_SHOW_TITLE_TAG2,'','','',_SHOW_TITLE_TAG)?>:</td>
							<td><?php echo $lists['MetaTitle']; ?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_SHOW_AUTHOR_TAG2,'','','',_SHOW_AUTHOR_TAG)?>:</td>
							<td><?php echo $lists['MetaAuthor']; ?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_SHOW_BASE_TAG2,'','','',_SHOW_BASE_TAG)?>:</td>
							<td><?php echo $lists['mtage_base']; ?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_REVISIT_TAG2,'','','',_REVISIT_TAG)?>:</td>
							<td><input class="text_area" type="text" name="config_mtage_revisit" size="10" value="<?php echo $row->config_mtage_revisit; ?>"/></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_DISABLE_GENERATOR_TAG2,'','','',_DISABLE_GENERATOR_TAG)?>:</td>
							<td><?php echo $lists['generator_off'];?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_EXT_IND_TAGS2,'','','',_EXT_IND_TAGS)?>:</td>
							<td><?php echo $lists['index_tag'];?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(_MAIL,"mail-page");
				?>
		<table width="100%">
			<tr>
				<td>
					<table class="sub_adminform">
						<tr>
							<td width="185"><?php echo _MAIL_METHOD?>:</td>
							<td><?php echo $lists['mailer']; ?></td>
						</tr>
						<tr>
							<td><?php echo _MAIL_FROM_ADR?>:</td>
							<td><input class="text_area" type="text" name="config_mailfrom" size="50" value="<?php echo $row->config_mailfrom; ?>"/></td>
						</tr>
						<tr>
							<td><?php echo _MAIL_FROM_NAME?>:</td>
							<td><input class="text_area" type="text" name="config_fromname" size="50" value="<?php echo $row->config_fromname; ?>"/></td>
						</tr>
						<tr>
							<td><?php echo _SENDMAIL_PATH?>:</td>
							<td><input class="text_area" type="text" name="config_sendmail" size="50" value="<?php echo $row->config_sendmail; ?>"/></td>
						</tr>
					</table>
				</td><td valign="top">
					<table class="sub_adminform">
						<tr>
							<td><?php echo mosToolTip(_USE_SMTP2,'','','',_USE_SMTP)?>:</td>
							<td><?php echo $lists['smtpauth']; ?></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_SMTP_USER2,'','','',_SMTP_USER)?>:</td>
							<td><input class="text_area" type="text" name="config_smtpuser" size="50" value="<?php echo $row->config_smtpuser; ?>"/></td>
						</tr>
						<tr>
							<td><?php echo mosToolTip(_SMTP_PASSWORD2,'','','',_SMTP_PASSWORD)?>:</td>
							<td><input class="text_area" type="text" name="config_smtppass" size="50" value="<?php echo $row->config_smtppass; ?>"/></td>
						</tr>
						<tr>
							<td><?php echo _SMTP_SERVER?>:</td>
							<td><input class="text_area" type="text" name="config_smtphost" size="50" value="<?php echo $row->config_smtphost; ?>"/></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(_CACHE,"cache-page");
				?>
		<table width="100%">
					<?php
					if(is_writeable($row->config_cachepath)) {
						?>
			<tr>
				<td width="300"><?php echo mosToolTip(_ENABLE_CACHE2,'','','',_ENABLE_CACHE)?>:</td>
				<td ><?php echo $lists['caching']; ?></td>
			</tr>
			<tr>
				<td><?php echo _CACHE_SYSTEM;?></td>
				<td><?php echo $lists['cache_handler'];?></td>
			</tr>
						<?php } ?>
			<tr id="memcache_persist" <?php echo ($row->config_cache_handler != 'memcache')? "style='display: none;'" : ""; ?>>
				<td><?php echo _MEMCACHE_PERSISTENT; ?></td>
				<td id="memcache_persist_value"><?php echo $lists['memcache_persist']; ?></td>

			</tr>
			<tr id="memcache_compress" <?php echo ($row->config_cache_handler != 'memcache')? "style='display: none;'" : ""; ?>>
				<td><?php echo _MEMORY_CACHE_COMPRESSION; ?></td>
				<td><?php echo $lists['memcache_compress']; ?></td>
			</tr>
			<tr id="memcache_server" <?php echo ($row->config_cache_handler != 'memcache')? "style='display: none;'" : ""; ?>>
				<td><?php echo _MEMCACHE_SERVER; ?></td>
				<td><?php echo _HOST; ?>:
					<input class="text_area" type="text" id="config_memcache_host" name="config_memcache_host" size="25" value="<?php echo $row->config_memcache_host; ?>" />
					<br /><br />
							<?php echo _PORT; ?>:
					<input class="text_area" type="text" id="config_memcache_port" name="config_memcache_port" size="6" value="<?php echo $row->config_memcache_port; ?>" />
				</td>
			</tr>
			<tr>
				<td><?php echo _CACHE_TIME?>:</td>
				<td><input class="text_area" type="text" name="config_cachetime" size="5" value="<?php echo $row->config_cachetime; ?>"/> <?php echo _SECONDS?></td>
			</tr>
					<?php
					if(is_writeable($row->config_cachepath)) {
						?>
			<tr>
				<td><?php echo mosToolTip(_CACHE_OPTIMIZATION2,'','','',_CACHE_OPTIMIZATION)?>:</td>
				<td><?php echo $lists['config_cache_opt']; ?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_CACHE_MENU2,'','','',_CACHE_MENU)?>:</td>
				<td><?php echo $lists['adm_menu_cache']; ?></td>
			</tr>
						<?php
					} else {
						?>				<tr>
				<td width="350"><?php echo _CANNOT_CACHE?>:</td>
				<td><font color="red"><b><?php echo _CANNOT_CACHE2?></b></font></td>
			</tr>
						<?php
					}
					?>
			<tr>
				<td><?php echo mosToolTip(_CACHE_KEY_TOOLTIP,'','','',_CACHE_KEY_TEXT)?>:</td>
				<td><input type="text" READONLY name="config_cache_key_false" size="50" disable="true" value="<?php echo $row->config_cache_key; ?>"/>
			</tr>
			<tr>
				<td><?php echo _CACHE_DIR?>:</td>
				<td><input class="text_area" type="text" name="config_cachepath" size="50" value="<?php echo $row->config_cachepath; ?>"/>
							<?php
							if(is_writeable($row->config_cachepath)) {
								echo mosToolTip(_CACHE_DIR2);
							} else {
								echo mosWarning(_CACHE_DIR3);
							}
							?>
				</td>
			</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->startTab("SEO","seo-page");
				?>
		<table width="100%">
			<tr>
				<td width="300"><?php echo mosToolTip(_SEF_URLS2,'','','',_SEF_URLS)?>:</td>
				<td><?php echo $lists['sef']; ?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_DYNAMIC_PAGETITLES2,'','','',_DYNAMIC_PAGETITLES)?>:</td>
				<td><?php echo $lists['pagetitles']; ?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_DISABLE_PATHWAY_ON_FRONT2,'','','',_DISABLE_PATHWAY_ON_FRONT)?>:</td>
				<td><?php echo $lists['config_pathway_clean']; ?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_TITLE_ORDER2,'','','',_TITLE_ORDER)?>:</td>
				<td><?php echo $lists['pagetitles_first'];?></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_TITLE_SEPARATOR2,'','','',_TITLE_SEPARATOR)?>:</td>
				<td><input class="text_area" type="text" name="config_tseparator" size="5" value="<?php echo $row->config_tseparator; ?>"/></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_INDEX_PRINT_PAGE2,'','','',_INDEX_PRINT_PAGE)?>:</td>
				<td><?php echo $lists['index_print'];?></td>
			</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->startTab("CAPTCHA","captcha-page");
				?>
		<table width="100%">
			<tr>
				<td width="300"><?php echo mosToolTip(_ADMIN_LOGIN_COUNTER2,'','','',_ADMIN_LOGIN_COUNTER)?>:</td>
				<td><input class="text_area" style="width:60px;" type="text" name="config_admin_bad_auth" size="60" value="<?php echo $row->config_admin_bad_auth;?>"/></td>
			</tr>
			<tr>
				<td><?php echo mosToolTip(_ADMIN_CAPTCHA2,'','','',_ADMIN_CAPTCHA)?>:</td>
				<td><?php echo $lists['captcha'];?></td>
			</tr>
		</table>
				<?php
				$tabs->endTab();
				$tabs->endPane();
				?>

		<input type="hidden" name="option" value="<?php echo $option; ?>"/>
		<input type="hidden" name="config_absolute_path" value="<?php echo $row->config_absolute_path; ?>"/>
		<input type="hidden" name="config_live_site" value="<?php echo $row->config_live_site; ?>"/>
		<input type="hidden" name="config_secret" value="<?php echo $row->config_secret; ?>"/>
		<input type="hidden" name="config_auto_activ_login" value="0"/>
		<input type="hidden" name="config_multilingual_support" value="<?php echo $row->config_multilingual_support ?>" />
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1" />
	</form>
</div>
		<?php
	}
}
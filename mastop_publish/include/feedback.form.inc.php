<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Formulário de Conteúdo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
if (!defined('XOOPS_ROOT_PATH')) {
	die("Ooops!");
}
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$feedbackform = new XoopsThemeForm($form['titulo'], "mpu_feedbackform", $_SERVER['PHP_SELF'], "post");
$feedbackform->addElement(new XoopsFormText(MPU_ADM_YNAME, "yname", 35, 50, $xoopsUser->getVar('name')));
$feedbackform->addElement(new XoopsFormText(MPU_ADM_YEMAIL, "yemail", 35, 50, $xoopsConfig['adminmail']));
$feedbackform->addElement(new XoopsFormText(MPU_ADM_YSITE, "ydomain", 35, 50, XOOPS_URL));
$feedback_category_tray = new XoopsFormElementTray(MPU_ADM_FEEDTYPE, "&nbsp;&nbsp;&nbsp;");
$category_select = new XoopsFormSelect("", "feedback_type", MPU_ADM_TSUGGESTION);
$category_select->addOptionArray(array(MPU_ADM_TSUGGESTION=>MPU_ADM_TSUGGESTION, MPU_ADM_TBUGS=>MPU_ADM_TBUGS, MPU_ADM_TESTIMONIAL=>MPU_ADM_TESTIMONIAL, MPU_ADM_TFEATURES=>MPU_ADM_TFEATURES, MPU_ADM_TOTHERS=>MPU_ADM_TOTHERS));
$feedback_category_tray->addElement($category_select);
$feedback_category_tray->addElement(new XoopsFormText(MPU_ADM_TOTHERS, "feedback_other", 25, 50));
$feedbackform->addElement($feedback_category_tray);
if(!$xoopsModuleConfig['mpu_conf_wysiwyg']){
	$feedbackform->addElement(new XoopsFormDhtmlTextArea(MPU_ADM_DESC, "feedback_content"));
}else{
	$feedbackwysiwyg_url = XOOPS_URL.$xoopsModuleConfig['mpu_conf_wysiwyg_path'];
	if($xoopsModuleConfig['mpu_conf_gzip']){
		echo '
		<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="'.$feedbackwysiwyg_url.'/tiny_mce_gzip.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE_GZ.init({
    plugins : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_plugins'].'",
		themes : "advanced",
		languages : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_lang'].'",
		disk_cache : true,
		debug : false
});
</script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		language : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_lang'].'",
		editor_selector : "mpu_wysiwyg",
		disk_cache : true,
		debug : false,
		plugins : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_plugins'].'",
		theme_advanced_buttons1_add_before : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt1b'].'",
		theme_advanced_buttons1_add : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt1'].'",
		theme_advanced_buttons2_add : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt2'].'",
		theme_advanced_buttons2_add_before: "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt2b'].'",
		theme_advanced_buttons3_add_before : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt3b'].'",
		theme_advanced_buttons3_add : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt3'].'",
		theme_advanced_buttons4 : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt4'].'",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		content_css : "'.XOOPS_THEME_URL.'/'.$GLOBALS['xoopsConfig']['theme_set'].'/style.css",
	    plugin_insertdate_dateFormat : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_frmtdata'].'",
	    plugin_insertdate_timeFormat : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_frmthora'].'",
		extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		external_link_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_files_list.js.php",
		external_image_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_image_list.js.php",
		media_external_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_media_list.js.php",
		file_browser_callback : "mpu_chama_browser",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		nonbreaking_force_tab : true,
		apply_source_formatting : true,
		plugin_keyword_list : "'.MPU_ADM_BANNER.'={banner};'.MPU_ADM_SITENAME.'={sitename};'.MPU_ADM_SLOGAN.'={slogan};'.MPU_ADM_ADMINMAIL.'={adminmail};'.MPU_ADM_SITEURL.'={xoops_url};'.MPU_ADM_UID.'={uid};'.MPU_ADM_USERNAME.'={name};'.MPU_ADM_USERLOGIN.'={uname};'.MPU_ADM_UEMAIL.'={email};'.MPU_ADM_USERURL.'={url};'.MPU_ADM_USERPOSTS.'={posts};",
		convert_urls : false
	});

	function mpu_chama_browser(field_name, url, type, win) {
	if(type == "image"){
	tinyMCE.addToLang("",{
	browser_procurar : "'.MPU_ADM_BROWSER_TITULO.'",
	browser_gimg_title : "'._IMGMANAGER.'",
	browser_ger_imagens : "'.MPU_ADM_BROWSER_GER_IMG.'",
	browser_nova_imagem : "'.MPU_ADM_BROWSER_NIMG.'",
	browser_nova_cat : "'.MPU_ADM_BROWSER_NCAT.'"
	});
	tinyMCE.openWindow({
						file : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/browser_image.php",
						width : 550 + tinyMCE.getLang("lang_media_delta_width", 0),
						height : 380 + tinyMCE.getLang("lang_media_delta_height", 0),
						close_previous : "no"
					}, {
						win: win,
						campo: field_name,
						url : url,
						inline : "yes",
						resizable : "yes",
						editor_id: "feedback_content"
				});
	}else if(type == "media"){
	tinyMCE.addToLang("",{
	browser_procurar : "'.MPU_ADM_BROWSER_TITULO.'",
	browser_ger_medias : "'.MPU_ADM_BROWSER_GER_MED.'",
	browser_media_title : "'.MPU_ADM_BROWSER_MED_TITULO.'",
	browser_nova_media : "'.MPU_ADM_NMEDIA.'"
	});
	tinyMCE.openWindow({
						file : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/browser_media.php",
						width : 550 + tinyMCE.getLang("lang_media_delta_width", 0),
						height : 380 + tinyMCE.getLang("lang_media_delta_height", 0),
						close_previous : "no"
					}, {
						win: win,
						campo: field_name,
						url : url,
						inline : "yes",
						resizable : "yes",
						editor_id: "feedback_content"
				});
	}else if(type == "file"){
	tinyMCE.addToLang("",{
	browser_procurar : "'.MPU_ADM_BROWSER_TITULO.'",
	browser_ger_files : "'.MPU_ADM_BROWSER_GER_FIL.'",
	browser_file_title : "'.MPU_ADM_BROWSER_FIL_TITULO.'",
	browser_novo_file : "'.MPU_ADM_NFILE.'"
	});
	tinyMCE.openWindow({
						file : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/browser_files.php",
						width : 550 + tinyMCE.getLang("lang_media_delta_width", 0),
						height : 380 + tinyMCE.getLang("lang_media_delta_height", 0),
						close_previous : "no"
					}, {
						win: win,
						campo: field_name,
						url : url,
						inline : "yes",
						resizable : "yes",
						editor_id: "feedback_content"
				});
	}
	return false;
}
</script>
<!-- /TinyMCE -->
		';
	}else{
		echo '
<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="'.$feedbackwysiwyg_url.'/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		language : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_lang'].'",
		editor_selector : "mpu_wysiwyg",
		disk_cache : true,
		debug : false,
		plugins : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_plugins'].'",
		theme_advanced_buttons1_add_before : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt1b'].'",
		theme_advanced_buttons1_add : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt1'].'",
		theme_advanced_buttons2_add : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt2'].'",
		theme_advanced_buttons2_add_before: "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt2b'].'",
		theme_advanced_buttons3_add_before : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt3b'].'",
		theme_advanced_buttons3_add : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt3'].'",
		theme_advanced_buttons4 : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_bt4'].'",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		content_css : "'.XOOPS_THEME_URL.'/'.$GLOBALS['xoopsConfig']['theme_set'].'/style.css",
	    plugin_insertdate_dateFormat : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_frmtdata'].'",
	    plugin_insertdate_timeFormat : "'.$xoopsModuleConfig['mpu_conf_wysiwyg_frmthora'].'",
		extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		external_link_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_link_list.js",
		external_image_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_image_list.js.php",
		media_external_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_media_list.js",
		file_browser_callback : "mpu_chama_browser",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		nonbreaking_force_tab : true,
		apply_source_formatting : true,
		plugin_keyword_list : "'.MPU_ADM_BANNER.'={banner};'.MPU_ADM_SITENAME.'={sitename};'.MPU_ADM_SLOGAN.'={slogan};'.MPU_ADM_ADMINMAIL.'={adminmail};'.MPU_ADM_SITEURL.'={xoops_url};'.MPU_ADM_UID.'={uid};'.MPU_ADM_USERNAME.'={name};'.MPU_ADM_USERLOGIN.'={uname};'.MPU_ADM_UEMAIL.'={email};'.MPU_ADM_USERURL.'={url};'.MPU_ADM_USERPOSTS.'={posts};",
		convert_urls : false
	});
		function mpu_chama_browser(field_name, url, type, win) {
	if(type == "image"){
	tinyMCE.addToLang("",{
	browser_procurar : "'.MPU_ADM_BROWSER_TITULO.'",
	browser_gimg_title : "'._IMGMANAGER.'",
	browser_ger_imagens : "'.MPU_ADM_BROWSER_GER_IMG.'",
	browser_nova_imagem : "'.MPU_ADM_BROWSER_NIMG.'",
	browser_nova_cat : "'.MPU_ADM_BROWSER_NCAT.'"
	});
	tinyMCE.openWindow({
						file : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/browser_image.php",
						width : 550 + tinyMCE.getLang("lang_media_delta_width", 0),
						height : 380 + tinyMCE.getLang("lang_media_delta_height", 0),
						close_previous : "no"
					}, {
						win: win,
						campo: field_name,
						url : url,
						inline : "yes",
						resizable : "yes",
						editor_id: "feedback_content"
				});
	}else if(type == "media"){
	tinyMCE.addToLang("",{
	browser_procurar : "'.MPU_ADM_BROWSER_TITULO.'",
	browser_ger_medias : "'.MPU_ADM_BROWSER_GER_MED.'",
	browser_media_title : "'.MPU_ADM_BROWSER_MED_TITULO.'",
	browser_nova_media : "'.MPU_ADM_NMEDIA.'"
	});
	tinyMCE.openWindow({
						file : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/browser_media.php",
						width : 550 + tinyMCE.getLang("lang_media_delta_width", 0),
						height : 380 + tinyMCE.getLang("lang_media_delta_height", 0),
						close_previous : "no"
					}, {
						win: win,
						campo: field_name,
						url : url,
						inline : "yes",
						resizable : "yes",
						editor_id: "feedback_content"
				});
	}else if(type == "file"){
	tinyMCE.addToLang("",{
	browser_procurar : "'.MPU_ADM_BROWSER_TITULO.'",
	browser_ger_files : "'.MPU_ADM_BROWSER_GER_FIL.'",
	browser_file_title : "'.MPU_ADM_BROWSER_FIL_TITULO.'",
	browser_novo_file : "'.MPU_ADM_NFILE.'"
	});
	tinyMCE.openWindow({
						file : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/browser_files.php",
						width : 550 + tinyMCE.getLang("lang_media_delta_width", 0),
						height : 380 + tinyMCE.getLang("lang_media_delta_height", 0),
						close_previous : "no"
					}, {
						win: win,
						campo: field_name,
						url : url,
						inline : "yes",
						resizable : "yes",
						editor_id: "feedback_content"
				});
	}
	return false;
}
</script>
<!-- /TinyMCE -->';
	}
	$textarea = new XoopsFormTextArea(MPU_ADM_DESC, "feedback_content", "", 20);
	$textarea->setExtra("style='width: 100%' class='mpu_wysiwyg'");
	$feedbackform->addElement($textarea);
}
$feedbackform->addElement(new XoopsFormHidden('op', $form['op']));
$feedbackbotoes_tray = new XoopsFormElementTray("", "&nbsp;&nbsp;");
$feedbackbotao_cancel = new XoopsFormButton("", "cancelar", _CANCEL);
$feedbackbotoes_tray->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
$feedbackbotao_cancel->setExtra("onclick=\"document.location= '".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php'\"");
$feedbackbotoes_tray->addElement($feedbackbotao_cancel);
$feedbackform->addElement($feedbackbotoes_tray);
?>
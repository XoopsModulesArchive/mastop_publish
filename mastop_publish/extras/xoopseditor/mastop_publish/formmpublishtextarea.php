<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo para Utilização do Editor Mastop Publish no site
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
class XoopsFormMPublishTextArea extends XoopsFormElement {

    var $value;
    var $name;
    var $width = "100%";
    var $height = "400px";
    var $moduleDir = "mastop_publish";

    /**
	 * Constructor
	 *
     * @param	array   $configs  Editor Options
	 */
    function XoopsFormMPublishTextArea($configs) {

        if(!empty($configs)) {
            foreach($configs as $key => $val) {
                if (method_exists($this, 'set'.Ucfirst($key))) {
                    $this->{'set'.Ucfirst($key)}($val);
                } else {
                    $this->$key = $val;
                }
            }
        }
    }

    function setConfig($configs) {
        foreach($configs as $key=>$val){
            $this->$key = $val;
        }
    }

    function getName() {
        return $this->name;
    }

    function setName($value) {
        $this->name = $value;
    }

    function getmoduleDir() {
        return $this->moduleDir;
    }

    function setmoduleDir($value) {
        $this->moduleDir = $value;
    }

    function getValue() {
        return $this->value;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setWidth($width) {
        if(!empty($width)){
            $this->width = $width;
        }
    }

    function getWidth() {
        return $this->width;
    }

    function setHeight($height)
    {
        if(!empty($height)){
            $this->height = $height;
        }
    }
    function getHeight()
    {
        return $this->height;
    }

    /**
    * Prepare HTML for output
    *
    * @return string HTML
    */

    function render() {
        global $xoopsUser, $xoopsConfig;
        if (file_exists(XOOPS_ROOT_PATH."/modules/".$this->getmoduleDir()."/language/".$xoopsConfig['language']."/modinfo.php") ) {
            include_once(XOOPS_ROOT_PATH."/modules/".$this->getmoduleDir()."/language/".$xoopsConfig['language']."/modinfo.php");
            include_once(XOOPS_ROOT_PATH."/modules/".$this->getmoduleDir()."/language/".$xoopsConfig['language']."/admin.php");
        } else {
            include_once(XOOPS_ROOT_PATH."/modules/".$this->getmoduleDir()."/language/portuguesebr/modinfo.php");
            include_once(XOOPS_ROOT_PATH."/modules/".$this->getmoduleDir()."/language/portuguesebr/admin.php");
        }
        $module_handler =& xoops_gethandler('module');
        $module =& $module_handler->getByDirname(MPU_MOD_DIR);
        $config_handler =& xoops_gethandler('config');
        $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
        $groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $module_id = $module->getVar('mid');
        $url = XOOPS_URL.$moduleConfig['mpu_conf_wysiwyg_path'];
        if (!empty($xoopsUser) && is_object($xoopsUser) && $moduleConfig['mpu_conf_wysiwyg']) {
            if (!$xoopsUser->isAdmin()) {
                echo '
<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="'.$url.'/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
		mode : "textareas",
		theme : "simple",
		language : "'.$moduleConfig['mpu_conf_wysiwyg_lang'].'",
		editor_selector : "mpu_wysiwyg",
		disk_cache : true,
		debug : false,
		plugins : "'.$moduleConfig['mpu_conf_wysiwyg_plugins'].'",
		theme_advanced_buttons1_add_before : "'.$moduleConfig['mpu_conf_wysiwyg_bt1b'].'",
		theme_advanced_buttons1_add : "'.$moduleConfig['mpu_conf_wysiwyg_bt1'].'",
		theme_advanced_buttons2_add : "'.$moduleConfig['mpu_conf_wysiwyg_bt2'].'",
		theme_advanced_buttons2_add_before: "'.$moduleConfig['mpu_conf_wysiwyg_bt2b'].'",
		theme_advanced_buttons3_add_before : "'.$moduleConfig['mpu_conf_wysiwyg_bt3b'].'",
		theme_advanced_buttons3_add : "'.$moduleConfig['mpu_conf_wysiwyg_bt3'].'",
		theme_advanced_buttons4 : "'.$moduleConfig['mpu_conf_wysiwyg_bt4'].'",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		content_css : "'.XOOPS_THEME_URL.'/'.$GLOBALS['xoopsConfig']['theme_set'].'/style.css",
	    plugin_insertdate_dateFormat : "'.$moduleConfig['mpu_conf_wysiwyg_frmtdata'].'",
	    plugin_insertdate_timeFormat : "'.$moduleConfig['mpu_conf_wysiwyg_frmthora'].'",
		extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]",
		theme_advanced_resize_horizontal : true,
		theme_advanced_resizing : true,
		nonbreaking_force_tab : true,
		apply_source_formatting : true,
		';
                if ($moduleConfig['mpu_conf_wysiwyg_bkg']) {
                    echo '
        convert_urls : false,
        setupcontent_callback : "fundoPadrao"
	   });
	   function fundoPadrao(editor_id, body) {
        body.style.backgroundImage="none";
        body.style.backgroundColor="#FFFFFF";
        body.style.textAlign="left";
        body.style.color="#000000";
        }';
                }else{
                    echo '
        convert_urls : false
	   });';
                }
                echo '
        function trocaEditor(id) {
	    var elm = document.getElementById(id);
	    if (tinyMCE.getInstanceById(id) == null)
		tinyMCE.execCommand("mceAddControl", false, id);
	    else
		tinyMCE.execCommand("mceRemoveControl", false, id);
        }
</script>
<!-- /TinyMCE -->';
            }else{
                if($moduleConfig['mpu_conf_gzip']){
                    echo '
		<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="'.$url.'/tiny_mce_gzip.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE_GZ.init({
    plugins : "'.$moduleConfig['mpu_conf_wysiwyg_plugins'].'",
		themes : "advanced",
		languages : "'.$moduleConfig['mpu_conf_wysiwyg_lang'].'",
		disk_cache : true,
		debug : false
});
</script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		language : "'.$moduleConfig['mpu_conf_wysiwyg_lang'].'",
		editor_selector : "mpu_wysiwyg",
		disk_cache : true,
		debug : false,
		plugins : "'.$moduleConfig['mpu_conf_wysiwyg_plugins'].'",
		theme_advanced_buttons1_add_before : "'.$moduleConfig['mpu_conf_wysiwyg_bt1b'].'",
		theme_advanced_buttons1_add : "'.$moduleConfig['mpu_conf_wysiwyg_bt1'].'",
		theme_advanced_buttons2_add : "'.$moduleConfig['mpu_conf_wysiwyg_bt2'].'",
		theme_advanced_buttons2_add_before: "'.$moduleConfig['mpu_conf_wysiwyg_bt2b'].'",
		theme_advanced_buttons3_add_before : "'.$moduleConfig['mpu_conf_wysiwyg_bt3b'].'",
		theme_advanced_buttons3_add : "'.$moduleConfig['mpu_conf_wysiwyg_bt3'].'",
		theme_advanced_buttons4 : "'.$moduleConfig['mpu_conf_wysiwyg_bt4'].'",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		content_css : "'.XOOPS_THEME_URL.'/'.$GLOBALS['xoopsConfig']['theme_set'].'/style.css",
	    plugin_insertdate_dateFormat : "'.$moduleConfig['mpu_conf_wysiwyg_frmtdata'].'",
	    plugin_insertdate_timeFormat : "'.$moduleConfig['mpu_conf_wysiwyg_frmthora'].'",
		extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]",
		external_link_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_files_list.js.php",
		external_image_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_image_list.js.php",
		media_external_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_media_list.js.php",
		file_browser_callback : "mpu_chama_browser",
		theme_advanced_resize_horizontal : true,
		theme_advanced_resizing : true,
		nonbreaking_force_tab : true,
		apply_source_formatting : true,
		';
                    if ($moduleConfig['mpu_conf_wysiwyg_bkg']) {
                        echo '
        convert_urls : false,
        setupcontent_callback : "fundoPadrao"
	   });
	   function fundoPadrao(editor_id, body) {
        body.style.backgroundImage="none";
        body.style.backgroundColor="#FFFFFF";
        body.style.textAlign="left";
        body.style.color="#000000";
        }';
                    }else{
                        echo '
        convert_urls : false
	   });';
                    }
                    if ($xoopsUser->isAdmin($module->getVar('mid'))) {
                        echo '
        function trocaEditor(id) {
	    var elm = document.getElementById(id);
	    if (tinyMCE.getInstanceById(id) == null)
		tinyMCE.execCommand("mceAddControl", false, id);
	    else
		tinyMCE.execCommand("mceRemoveControl", false, id);
        }
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
						editor_id: "'.$this->getName().'"
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
						editor_id: "'.$this->getName().'"
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
						editor_id: "'.$this->getName().'"
				});
	}
	return false;
}
</script>
<!-- /TinyMCE -->
		';
                    }else{
                        echo '
        function trocaEditor(id) {
	    var elm = document.getElementById(id);
	    if (tinyMCE.getInstanceById(id) == null)
		tinyMCE.execCommand("mceAddControl", false, id);
	    else
		tinyMCE.execCommand("mceRemoveControl", false, id);
        }
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
						editor_id: "'.$this->getName().'"
				});
	}
	return false;
}
</script>
<!-- /TinyMCE -->
		';
                    }
                }else{
                    echo '
<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="'.$url.'/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		language : "'.$moduleConfig['mpu_conf_wysiwyg_lang'].'",
		editor_selector : "mpu_wysiwyg",
		disk_cache : true,
		debug : false,
		plugins : "'.$moduleConfig['mpu_conf_wysiwyg_plugins'].'",
		theme_advanced_buttons1_add_before : "'.$moduleConfig['mpu_conf_wysiwyg_bt1b'].'",
		theme_advanced_buttons1_add : "'.$moduleConfig['mpu_conf_wysiwyg_bt1'].'",
		theme_advanced_buttons2_add : "'.$moduleConfig['mpu_conf_wysiwyg_bt2'].'",
		theme_advanced_buttons2_add_before: "'.$moduleConfig['mpu_conf_wysiwyg_bt2b'].'",
		theme_advanced_buttons3_add_before : "'.$moduleConfig['mpu_conf_wysiwyg_bt3b'].'",
		theme_advanced_buttons3_add : "'.$moduleConfig['mpu_conf_wysiwyg_bt3'].'",
		theme_advanced_buttons4 : "'.$moduleConfig['mpu_conf_wysiwyg_bt4'].'",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		content_css : "'.XOOPS_THEME_URL.'/'.$GLOBALS['xoopsConfig']['theme_set'].'/style.css",
	    plugin_insertdate_dateFormat : "'.$moduleConfig['mpu_conf_wysiwyg_frmtdata'].'",
	    plugin_insertdate_timeFormat : "'.$moduleConfig['mpu_conf_wysiwyg_frmthora'].'",
		extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]",
		external_link_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_link_list.js",
		external_image_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_image_list.js.php",
		media_external_list_url : "'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/include/mpu_media_list.js",
		file_browser_callback : "mpu_chama_browser",
		theme_advanced_resize_horizontal : true,
		theme_advanced_resizing : true,
		nonbreaking_force_tab : true,
		apply_source_formatting : true,
		';
                    if ($moduleConfig['mpu_conf_wysiwyg_bkg']) {
                        echo '
        convert_urls : false,
        setupcontent_callback : "fundoPadrao"
	   });
	   function fundoPadrao(editor_id, body) {
        body.style.backgroundImage="none";
        body.style.backgroundColor="#FFFFFF";
        body.style.textAlign="left";
        body.style.color="#000000";
        }';
                    }else{
                        echo '
        convert_urls : false
	   });';
                    }
                    if ($xoopsUser->isAdmin($module->getVar('mid'))) {
                        echo '
        function trocaEditor(id) {
	    var elm = document.getElementById(id);
	    if (tinyMCE.getInstanceById(id) == null)
		tinyMCE.execCommand("mceAddControl", false, id);
	    else
		tinyMCE.execCommand("mceRemoveControl", false, id);
        }
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
						editor_id: "'.$this->getName().'"
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
						editor_id: "'.$this->getName().'"
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
						editor_id: "'.$this->getName().'"
				});
	}
	return false;
}
</script>
<!-- /TinyMCE -->';
                    }else{
                        echo '
        function trocaEditor(id) {
	    var elm = document.getElementById(id);
	    if (tinyMCE.getInstanceById(id) == null)
		tinyMCE.execCommand("mceAddControl", false, id);
	    else
		tinyMCE.execCommand("mceRemoveControl", false, id);
        }
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
						editor_id: "'.$this->getName().'"
				});
	}
	return false;
}
</script>
<!-- /TinyMCE -->';
                    }
                }
            }
            // this is sooooo dirty and ugly, but the xoops-validation-script never gets the correct content, so I had to add a blank at the end of the textarea
            $form = "<textarea id=\"".$this->getName()."\" name=\"".$this->getName()."\" rows=\"1\" cols=\"1\" style=\"width:".$this->getWidth()."; height:".$this->getHeight()."\" class=\"mpu_wysiwyg\">".$this->getValue()." </textarea>";
            $form .= "<a href='javascript:trocaEditor(\"".$this->getName()."\")' style='float:right; padding:3px; color:#000000; background-color:#F0F0EE; border: 1px solid #CCCCCC; border-top:0px; margin-top:-1px'>".MPU_ADM_TOGGLE_EDITOR."</a>";
            $form .= $this->_renderSmileys(1);
        } else {
            $hiddenText = "xoopsHiddenText";
            $form = "<a name='moresmiley'></a><img onmouseover='style.cursor=\"hand\"' src='".XOOPS_URL."/images/url.gif' alt='url' onclick='xoopsCodeUrl(\"".$this->getName()."\", \"".htmlspecialchars(_ENTERURL, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERWEBTITLE, ENT_QUOTES)."\");' />&nbsp;<img onmouseover='style.cursor=\"hand\"' src='".XOOPS_URL."/images/email.gif' alt='email' onclick='javascript:xoopsCodeEmail(\"".$this->getName()."\", \"".htmlspecialchars(_ENTEREMAIL, ENT_QUOTES)."\");' />&nbsp;<img onclick='javascript:xoopsCodeImg(\"".$this->getName()."\", \"".htmlspecialchars(_ENTERIMGURL, ENT_QUOTES)."\", \"".htmlspecialchars(_ENTERIMGPOS, ENT_QUOTES)."\", \"".htmlspecialchars(_IMGPOSRORL, ENT_QUOTES)."\", \"".htmlspecialchars(_ERRORIMGPOS, ENT_QUOTES)."\");' onmouseover='style.cursor=\"hand\"' src='".XOOPS_URL."/images/imgsrc.gif' alt='imgsrc' />&nbsp;<img onmouseover='style.cursor=\"hand\"' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/imagemanager.php?target=".$this->getName()."\",\"imgmanager\",400,430);' src='".XOOPS_URL."/images/image.gif' alt='image' />&nbsp;<img src='".XOOPS_URL."/images/code.gif' onmouseover='style.cursor=\"hand\"' alt='code' onclick='javascript:xoopsCodeCode(\"".$this->getName()."\", \"".htmlspecialchars(_ENTERCODE, ENT_QUOTES)."\");' />&nbsp;<img onclick='javascript:xoopsCodeQuote(\"".$this->getName()."\", \"".htmlspecialchars(_ENTERQUOTE, ENT_QUOTES)."\");' onmouseover='style.cursor=\"hand\"' src='".XOOPS_URL."/images/quote.gif' alt='quote' /><br />\n";

            $sizearray = array("xx-small", "x-small", "small", "medium", "large", "x-large", "xx-large");
            $form .= "<select id='".$this->getName()."Size' onchange='setVisible(\"".$hiddenText."\");setElementSize(\"".$hiddenText."\",this.options[this.selectedIndex].value);'>\n";
            $form .= "<option value='SIZE'>"._SIZE."</option>\n";
            foreach ( $sizearray as $size ) {
                $form .=  "<option value='$size'>$size</option>\n";
            }
            $form .= "</select>\n";
            $fontarray = array("Arial", "Courier", "Georgia", "Helvetica", "Impact", "Verdana");
            $form .= "<select id='".$this->getName()."Font' onchange='setVisible(\"".$hiddenText."\");setElementFont(\"".$hiddenText."\",this.options[this.selectedIndex].value);'>\n";
            $form .= "<option value='FONT'>"._FONT."</option>\n";
            foreach ( $fontarray as $font ) {
                $form .= "<option value='$font'>$font</option>\n";
            }
            $form .= "</select>\n";
            $colorarray = array("00", "33", "66", "99", "CC", "FF");
            $form .= "<select id='".$this->getName()."Color' onchange='setVisible(\"".$hiddenText."\");setElementColor(\"".$hiddenText."\",this.options[this.selectedIndex].value);'>\n";
            $form .= "<option value='COLOR'>"._COLOR."</option>\n";
            foreach ( $colorarray as $color1 ) {
                foreach ( $colorarray as $color2 ) {
                    foreach ( $colorarray as $color3 ) {
                        $form .= "<option value='".$color1.$color2.$color3."' style='background-color:#".$color1.$color2.$color3.";color:#".$color1.$color2.$color3.";'>#".$color1.$color2.$color3."</option>\n";
                    }
                }
            }
            $form .= "</select><span id='".$hiddenText."'>"._EXAMPLE."</span>\n";
            $form .= "<br />\n";
            $form .= "<img onclick='javascript:setVisible(\"".$hiddenText."\");makeBold(\"".$hiddenText."\");' onmouseover='style.cursor=\"hand\"' src='".XOOPS_URL."/images/bold.gif' alt='bold' />&nbsp;<img onclick='javascript:setVisible(\"".$hiddenText."\");makeItalic(\"".$hiddenText."\");' onmouseover='style.cursor=\"hand\"' src='".XOOPS_URL."/images/italic.gif' alt='italic' />&nbsp;<img onclick='javascript:setVisible(\"".$hiddenText."\");makeUnderline(\"".$hiddenText."\");' onmouseover='style.cursor=\"hand\"' src='".XOOPS_URL."/images/underline.gif' alt='underline' />&nbsp;<img onclick='javascript:setVisible(\"".$hiddenText."\");makeLineThrough(\"".$hiddenText."\");' src='".XOOPS_URL."/images/linethrough.gif' alt='linethrough' onmouseover='style.cursor=\"hand\"' />&nbsp;&nbsp;<input type='text' id='".$this->getName()."Addtext' size='20' />&nbsp;<input type='button' onclick='xoopsCodeText(\"".$this->getName()."\", \"".$hiddenText."\", \"".htmlspecialchars(_ENTERTEXTBOX, ENT_QUOTES)."\")' class='formButton' value='"._ADD."' /><br /><br /><textarea id='".$this->getName()."' name='".$this->getName()."' onselect=\"xoopsSavePosition('".$this->getName()."');\" onclick=\"xoopsSavePosition('".$this->getName()."');\" onkeyup=\"xoopsSavePosition('".$this->getName()."');\" cols='50' rows='20' ".$this->getExtra()." style='width:100%; height: 400px;'>".$this->getValue()."</textarea><br />\n";
            $form .= $this->_renderSmileys(0);
        }
        return $form;

    }

    function _renderSmileys($visual)
    {
        if (!$visual) {
            $myts =& MyTextSanitizer::getInstance();
            $smiles =& $myts->getSmileys();
            $ret = '';
            if (empty($smileys)) {
                $db =& Database::getInstance();
                if ($result = $db->query('SELECT * FROM '.$db->prefix('smiles').' WHERE display=1')) {
                    while ($smiles = $db->fetchArray($result)) {
                        $ret .= "<img onclick='xoopsCodeSmilie(\"".$this->getName()."\", \" ".$smiles['code']." \");' onmouseover='style.cursor=\"hand\"' src='".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles['smile_url'], ENT_QUOTES)."' alt='' />";
                    }
                }
            } else {
                $count = count($smiles);
                for ($i = 0; $i < $count; $i++) {
                    if ($smiles[$i]['display'] == 1) {
                        $ret .= "<img onclick='xoopsCodeSmilie(\"".$this->getName()."\", \" ".$smiles[$i]['code']." \");' onmouseover='style.cursor=\"hand\"' src='".XOOPS_UPLOAD_URL."/".$myts->oopsHtmlSpecialChars($smiles['smile_url'])."' border='0' alt='' />";
                    }
                }
            }
            $ret .= "&nbsp;[<a href='#moresmiley' onclick='javascript:openWithSelfMain(\"".XOOPS_URL."/misc.php?action=showpopups&amp;type=smilies&amp;target=".$this->getName()."\",\"smilies\",300,475);'>"._MORE."</a>]";
        }else{
            $myts =& MyTextSanitizer::getInstance();
            $smiles =& $myts->getSmileys();
            $ret = '';
            if (empty($smileys)) {
                $db =& Database::getInstance();
                if ($result = $db->query('SELECT * FROM '.$db->prefix('smiles').' WHERE display=1')) {
                    while ($smiles = $db->fetchArray($result)) {
                        $ret .= "<img onclick=\"tinyMCE.execInstanceCommand('".$this->getName()."', 'mceInsertContent', false, '<img src=\'".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles['smile_url'], ENT_QUOTES)."\' />');\" onmouseover='style.cursor=\"hand\"' src='".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles['smile_url'], ENT_QUOTES)."' alt='".$smiles['emotion']."' />";
                    }
                }
            } else {
                $count = count($smiles);
                for ($i = 0; $i < $count; $i++) {
                    if ($smiles[$i]['display'] == 1) {
                        $ret .= "<img onclick=\"tinyMCE.execInstanceCommand('".$this->getName()."', 'mceInsertContent', false, '<img src=\'".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles[$i]['smile_url'], ENT_QUOTES)."\' />');\" onmouseover='style.cursor=\"hand\"' src='".XOOPS_UPLOAD_URL."/".$myts->oopsHtmlSpecialChars($smiles[$i]['smile_url'])."' border='0' alt='".$smiles[$i]['emotion']."' />";
                    }
                }
            }
        }
        return $ret;
    }

}
?>
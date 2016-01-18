<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Funções Padrão para o Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
// Administração
function mpu_adm_menu(){
	global $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
	$adm_url = XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/";
	$links[] = array(0 => XOOPS_URL.'/modules/system/admin.php?fct=preferences&op=showmod&mod='.$xoopsModule->getVar('mid'), 1 => _PREFERENCES);
	xoops_cp_header();
	echo '
<link rel="stylesheet" type="text/css" href="menu/style_menu.css" />
<script type="text/javascript" src="menu/jsdomenu.js"></script>
<script type="text/javascript" src="menu/jsdomenubar.js"></script>
<script type="text/javascript">
function createjsDOMenu() {
	mainMenu1 = new jsDOMenu(200);
	with (mainMenu1) {
		addMenuItem(new menuItem("'.MPU_MOD_MENU_ADD.'", "", "'.$adm_url.'index.php?op=novo"));
		addMenuItem(new menuItem("'.MPU_MOD_MENU_LST.'", "", "'.$adm_url.'index.php?op=listar"));
		addMenuItem(new menuItem("'.MPU_MOD_MENU_LNK.'", "", "'.$adm_url.'paginas.php"));
	}

	mainMenu2 = new jsDOMenu(150);
	with (mainMenu2) {
		addMenuItem(new menuItem("'._ADD.'", "", "'.$adm_url.'media.php?op=media_adicionar"));
		addMenuItem(new menuItem("'.MPU_MOD_MENU_GER.'", "", "'.$adm_url.'media.php"));
	}

	mainMenu3 = new jsDOMenu(150);
	with (mainMenu3) {
		addMenuItem(new menuItem("'._ADD.'", "", "'.$adm_url.'files.php?op=files_adicionar"));
		addMenuItem(new menuItem("'.MPU_MOD_MENU_GER.'", "", "'.$adm_url.'files.php"));
	}

	mainMenu4 = new jsDOMenu(150);
	with (mainMenu4) {
		addMenuItem(new menuItem("'.MPU_ADM_BLOCKS.'", "", "'.$adm_url.'blocksadmin.php"));
		addMenuItem(new menuItem("'._COMMENTS.'", "", "'.XOOPS_URL.'/modules/system/admin.php?fct=comments&module='.$xoopsModule->getVar('mid').'"));
		addMenuItem(new menuItem("'._PREFERENCES.'", "", "'.XOOPS_URL.'/modules/system/admin.php?fct=preferences&op=showmod&mod='.$xoopsModule->getVar('mid').'"));
	}

	menuBar = new jsDOMenuBar();
	with (menuBar) {
		addMenuBarItem(new menuBarItem("'.MPU_ADM_CONTENTS.'", mainMenu1, "contentid"));
		addMenuBarItem(new menuBarItem("'.MPU_ADM_BROWSER_GER_MED.'", mainMenu2, "mediaid"));
		addMenuBarItem(new menuBarItem("'.MPU_ADM_BROWSER_GER_FIL.'", mainMenu3, "fileid"));
		addMenuBarItem(new menuBarItem("'._OPTIONS.'", mainMenu4, "optid"));
	}
	menuBar.items.contentid.showIcon("page", "page", "page");
	menuBar.items.mediaid.showIcon("media", "media", "media");
	menuBar.items.fileid.showIcon("file", "file", "file");
	menuBar.items.optid.showIcon("opt", "opt", "opt");
	menuBar.moveTo(280, 81);
}
</script>
';
	$dir = MPU_FILES_PATH;
	$dir2 = MPU_MEDIA_PATH;
	$dir3 = MPU_HTML_PATH;
	if(!is_writable($dir)) {
		xoops_error(MPU_ADM_FILEERROR);
	}
	if(!is_writable($dir2)) {
		xoops_error(MPU_ADM_MEDIAERROR);
	}
	if(!is_writable($dir3)) {
		xoops_error(MPU_ADM_HTMLERROR);
	}
	if($xoopsModuleConfig['mpu_conf_wysiwyg'] && $xoopsModuleConfig['mpu_conf_gzip'] && !is_writable(XOOPS_ROOT_PATH.$xoopsModuleConfig['mpu_conf_wysiwyg_path'])) {
		xoops_error(sprintf(MPU_ADM_WYSIWYG_PATHERROR, XOOPS_ROOT_PATH.$xoopsModuleConfig['mpu_conf_wysiwyg_path']));
	}
}
function mpu_apagaPermissoes($id){
	global $xoopsModule, $moduleperm_handler;
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('gperm_itemid', $id));
	$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid')));
	$criteria->add(new Criteria('gperm_name', "mpu_mpublish_acesso"));
	if( $old_perms =& $moduleperm_handler->getObjects($criteria) ){
		foreach( $old_perms as $p ){
			$moduleperm_handler->delete($p);
		}
	}
	xoops_comment_delete($xoopsModule->getVar('mid'), $id);
	return true;
}

function mpu_apagaPermissoesPai($id){
	global $xoopsModule;
	include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_mpb_mpublish.class.php";
	$mpu_classe = new mpu_mpb_mpublish();
	$todos = $mpu_classe->PegaTudo(new Criteria("mpb_10_idpai", $id));
	if (!empty($todos)) {
		foreach ($todos as $v){
			mpu_apagaPermissoes($v->getVar("mpb_10_id"));
			xoops_comment_delete($xoopsModule->getVar('mid'), $v->getVar("mpb_10_id"));
		}
		return true;
	}
	return false;
}

function mpu_inserePermissao($id, $grupos_ids){
	global $xoopsModule, $moduleperm_handler;
	foreach( $grupos_ids as $gid ){
		$perm =& $moduleperm_handler->create();
		$perm->setVar('gperm_name', "mpu_mpublish_acesso");
		$perm->setVar('gperm_itemid', $id);
		$perm->setVar('gperm_groupid', $gid);
		$perm->setVar('gperm_modid', $xoopsModule->getVar('mid'));
		$moduleperm_handler->insert($perm);
	}
	return true;
}

function prepareContent($content){
	global $xoopsUser, $xoopsConfig;
	if(is_object($xoopsUser)){
		if ($xoopsUser->cleanVars()) {
			foreach ($xoopsUser->cleanVars as $k => $v) {
				$content = str_replace("{".$k."}", $v, $content);
			};
		}
	}
	foreach ($xoopsConfig as $k => $v){
		if(!is_array($v)){
			$content = str_replace("{".$k."}", $v, $content);
		}
	}
	$content = str_replace("{banner}", xoops_getbanner(), $content);
	if (!empty($_GET['busca']) && is_array($_GET['busca'])) {
		$search_string = MPU_MOD_HIGHLIGHT_SEARCH;
		$found = 0;
		$bgs = array("#ffff66", "#a0ffff", "#99ff99", "#ff9999", "#880000", "#00aa00", "#886800", "#004699", "#990099");
		$colors = array("black", "black", "black", "black", "white", "white", "white", "white", "white");
		$ctrl = 0;
		$busca = array_unique($_GET['busca']);
		foreach ($busca as $v){
			if(stristr(strip_tags($content), $v)){
				$cfundo = $bgs[$ctrl];
				$ctexto = $colors[$ctrl];
				$busca[0] = "~".$v."(?![^<]*>)~";
				$busca[1] = "~".strtolower($v)."(?![^<]*>)~";
				$busca[2] = "~".strtoupper($v)."(?![^<]*>)~";
				$busca[3] = "~".ucfirst(strtolower($v))."(?![^<]*>)~";
				$troca[0] = '<span style="font-weight:bold; color: '.$ctexto.'; background-color: '.$cfundo.';">'.$v."</span>";
				$troca[1] = '<span style="font-weight:bold; color: '.$ctexto.'; background-color: '.$cfundo.';">'.strtolower($v)."</span>";
				$troca[2] = '<span style="font-weight:bold; color: '.$ctexto.'; background-color: '.$cfundo.';">'.strtoupper($v)."</span>";
				$troca[3] = '<span style="font-weight:bold; color: '.$ctexto.'; background-color: '.$cfundo.';">'.ucfirst(strtolower($v))."</span>";
				$content = preg_replace($busca, $troca, $content);
				$search_string .= '<span style="font-weight:bold; color: '.$ctexto.'; background-color: '.$cfundo.';">'.$v."</span>, ";
				$found = 1;
				if ($ctrl == 8) {
					$ctrl = 0;
				}else{
					$ctrl++;
				}
			}
		}
		if ($found) {
			$search_string = substr($search_string, 0, -2)."<br /><br />";
			$content = $search_string.$content;
		}
	}
	return $content;
}

// Módulo


?>
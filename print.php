<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Página para impressão
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include '../../mainfile.php';
$xoopsLogger->activated = false;
include_once "header.php";
$tac = (isset($_GET['tac'])) ? $_GET['tac'] : 0;
$tac = (is_int($tac)) ? $tac : str_replace("_"," ", $tac);
if(!$tac){
	redirect_header(XOOPS_URL, 2, MPU_MAI_404);
}else{
	$mpu_classe = new mpu_mpb_mpublish($tac);
	if (!$mpu_classe->getVar("mpb_10_id")) {
		redirect_header(XOOPS_URL, 2, MPU_MAI_404);
	}else{
		$groups = (!empty($xoopsUser) && is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$gperm_handler =& xoops_gethandler('groupperm');
		if (!$gperm_handler->checkRight("mpu_mpublish_acesso", $mpu_classe->getVar("mpb_10_id"), $groups, $xoopsModule->getVar('mid'))) {
			redirect_header(XOOPS_URL, 3, _NOPERM);
			exit();
		}
		if ($xoopsModuleConfig['mpu_conf_navigation']) {
			$navigation = $mpu_classe->geraNavigation();
		}else{
			$navigation = "";
		}
		if($mpu_classe->getVar("mpb_30_arquivo") != "" && substr($mpu_classe->getVar("mpb_30_arquivo"), 0, 7) == "http://"){
			$content = '<iframe src ="'.$mpu_classe->getVar("mpb_30_arquivo").'" width="'.$xoopsModuleConfig['mpu_conf_iframe_width'].'" height="'.$xoopsModuleConfig['mpu_conf_iframe_height'].'" scrolling="auto" frameborder="0"></iframe>';
			$mpb_30_titulo = $mpu_classe->getVar("mpb_30_titulo");
		}elseif ($mpu_classe->getVar("mpb_30_arquivo") != "" && $mpu_classe->getVar("mpb_35_conteudo") == ""){
			$pageContent = MPU_HTML_PATH."/".$mpu_classe->getVar("mpb_30_arquivo");
			if(file_exists($pageContent)){
				ob_start();
				if(substr(strtolower($mpu_classe->getVar("mpb_30_arquivo")), -3) == "php"){
					include($pageContent);
				}else{
					readfile($pageContent);
				}
				$content = ob_get_contents();
				ob_end_clean();
				if (substr(strtolower($mpu_classe->getVar("mpb_30_arquivo")), -3) == "txt") {
					$content = nl2br($content);
				}
				$content = prepareContent($content);
			}
		}else{
			$content = prepareContent($mpu_classe->getVar("mpb_35_conteudo", "n"));
		}
		$titulo = $mpu_classe->getVar("mpb_30_titulo");
	}
	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";
	echo '<html><head>';
	echo '
	<style type="text/css">
	a{text-decoration: none; color:#000000 }
	</style>
	';
	echo '<meta http-equiv="Content-Type" content="text/html; charset='._CHARSET.'" />';
	echo '<title>'.$xoopsConfig['sitename'].' - '.$titulo.'</title>';
	echo '<meta name="AUTHOR" content="'.$xoopsConfig['sitename'].'" />';
	echo '<meta name="COPYRIGHT" content="Copyright (c) '.date("Y").' by '.$xoopsConfig['sitename'].'" />';
	echo '<meta name="DESCRIPTION" content="'.$xoopsConfig['slogan'].'" />';
	echo '<meta name="GENERATOR" content="Mastop Publish V '.round($xoopsModule->getVar('version') / 100, 2).'-'.XOOPS_VERSION.'" />';
	echo '<body bgcolor="#ffffff" text="#000000" onload="window.print()">
    	<table border="0"><tr><td align="center">
    	<table border="0" width="640" cellpadding="0" cellspacing="1" bgcolor="#000000"><tr><td>
    	<table border="0" width="640" cellpadding="20" cellspacing="1" bgcolor="#ffffff"><tr><td><span style="color:#000000; font-size:15px;">'.$navigation.'</span></td></tr>
    	<tr><td align="center">
    	<img src="'.XOOPS_URL.'/images/logo.gif" border="0" alt="" /><br /><br />
    	<h3>'.$titulo.'</h3></td></tr>';
	echo '<tr valign="top"><td>';
	echo $content;
	echo '</td></tr></table></td></tr></table>';
	echo '<br /><a href="'.XOOPS_URL.'/">'.$xoopsConfig['sitename'].'</a><br /><br />'.$mpu_classe->pegaLink().'<br />
    	</td></tr></table>
    	</body>
    	</html>
    	';
}
?>

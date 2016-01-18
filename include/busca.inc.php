<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Função de Busca no Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================

function mpu_mpublish_busca($queryarray, $andor, $limit, $offset, $userid){
	global $xoopsDB, $xoopsConfig, $xoopsUser;
	$module_handler =& xoops_gethandler('module');
	$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$moduleperm_handler =& xoops_gethandler('groupperm');
	$mpu_module =& $module_handler->getByDirname(MPU_MOD_DIR);
	$MyPages = $moduleperm_handler->getItemIds("mpu_mpublish_acesso", $groups, $mpu_module->getVar('mid'));
	$query_str = "";
	include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_mpb_mpublish.class.php";
	$sql = "SELECT mpb_10_id, mpb_10_ordem FROM ".$xoopsDB->prefix(MPU_MOD_TABELA1)." WHERE mpb_11_visivel < 4 AND mpb_12_semlink = 0";
	if ( $userid != 0 ) {
		$sql .= " AND uid=".$userid." ";
	}
	if ( is_array($queryarray) && $count = count($queryarray) ) {
		$query_str .= "&busca[]=".$queryarray[0];
		$sql .= " AND ((mpb_35_conteudo LIKE '%".$queryarray[0]."%' OR mpb_30_menu LIKE '%".$queryarray[0]."%' OR mpb_30_titulo LIKE '%".$queryarray[0]."%')";
		for($i=1;$i<$count;$i++){
			$sql .= " $andor ";
			$sql .= "(mpb_35_conteudo LIKE '%".$queryarray[$i]."%' OR mpb_30_menu LIKE '%".$queryarray[$i]."%' OR mpb_30_titulo LIKE '%".$queryarray[$i]."%')";
			$query_str .= "&busca[]=".$queryarray[$i];
		}
		$sql .= ")";
	}

	$sql .= " ORDER BY mpb_10_ordem ASC";
	$result = $xoopsDB->query($sql,$limit,$offset);
	$ret = array();
	$contents = array();
	while($myrow = $xoopsDB->fetchArray($result)){
		if (!in_array($myrow['mpb_10_id'], $MyPages)) continue;
		$contents[$myrow['mpb_10_id']] = $myrow['mpb_10_ordem'];
	}
	$sql = "SELECT mpb_10_id, mpb_10_ordem, mpb_30_arquivo FROM ".$xoopsDB->prefix(MPU_MOD_TABELA1)." WHERE mpb_11_visivel < 4 AND mpb_12_semlink = 0 AND mpb_30_arquivo != '' AND SUBSTRING(mpb_30_arquivo, 1, 4) != 'http'";
	if ( $userid != 0 ) {
		$sql .= " AND uid=".$userid." ";
	}
	$result = $xoopsDB->query($sql);
	while($myrow = $xoopsDB->fetchArray($result)){
		if (!in_array($myrow['mpb_10_id'], $MyPages)) continue;
		$pageContent = MPU_HTML_PATH."/".$myrow["mpb_30_arquivo"];
		if(file_exists($pageContent)){
			ob_start();
			if(substr(strtolower($myrow["mpb_30_arquivo"]), -3) == "php"){
				include($pageContent);
			}else{
				readfile($pageContent);
			}
			$content = ob_get_contents();
			ob_end_clean();
			$content = strip_tags($content);
			if ( is_array($queryarray) && $count = count($queryarray) ) {
				$ver_content = stristr($content, $queryarray[0]);
				if (!$ver_content && $andor == "AND") continue;
				for($i=1;$i<$count;$i++){
					if ($ver_content && $andor == "OR")	break;
					if (!$ver_content && $andor == "AND") break;
					$ver_content = stristr($content, $queryarray[$i]);
				}
			}
			if ($ver_content) {
				$contents[$myrow['mpb_10_id']] = $myrow['mpb_10_ordem'];
			}
		}
	}
	if (is_array($contents) && count($contents) > 0) {
		$i = 0;
		asort($contents);
		foreach ($contents as $k => $v) {
			$mpu_classe = new mpu_mpb_mpublish($k);
			$ret[$i]['image'] = "images/publish.gif' align='absmiddle";
			$ret[$i]['link'] = $mpu_classe->pegaLink().$query_str;
			$ret[$i]['title'] = $mpu_classe->getVar("mpb_30_menu");
			$ret[$i]['uid'] = "0";
			if ($i == ($limit-1)) {
				return $ret;
			}
			$i++;
		}
	}
	return $ret;
}
?>

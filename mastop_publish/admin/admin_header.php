<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Header com includes padrões para a Admin do Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id: admin_header.php,v 1.3 2007/05/15 09:17:05 topet05 Exp $
### =============================================================
include '../../../include/cp_header.php';
if ( file_exists("../language/".$xoopsConfig['language']."/modinfo.php") ) {
	include_once("../language/".$xoopsConfig['language']."/modinfo.php");
} else {
	include_once("../language/portuguesebr/modinfo.php");
}
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_mpb_mpublish.class.php";
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_med_media.class.php";
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_fil_files.class.php";
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_cfi_contentfiles.class.php";
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/funcoes.inc.php";
$c['lang']['showhidemenu'] = MPU_ADM_SHOWHIDEMENU;
?>
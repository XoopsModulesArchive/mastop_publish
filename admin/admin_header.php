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
### $Id$
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
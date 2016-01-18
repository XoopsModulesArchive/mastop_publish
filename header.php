<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Header do Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include XOOPS_ROOT_PATH."/header.php";
if (!defined('MPU_MOD_DIR')) {
	if ( file_exists("language/".$xoopsConfig['language']."/modinfo.php") ) {
		include_once("language/".$xoopsConfig['language']."/modinfo.php");
	} else {
		include_once("language/portuguesebr/modinfo.php");
	}
}
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_mpb_mpublish.class.php";
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/funcoes.inc.php";
?>
<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Imagem de CAPTCHA
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
if (file_exists("../../../../mainfile.php")) {
	include_once("../../../../mainfile.php");
}elseif (file_exists("../../../mainfile.php")){
	include_once("../../../mainfile.php");
}
$xoopsLogger->activated = false;
include_once XOOPS_ROOT_PATH."/header.php";
include_once('php-captcha.inc.php');
$aFonts = array('fonts/font1.ttf', 'fonts/font2.ttf', 'fonts/font3.ttf');
$oPhpCaptcha = new PhpCaptcha($aFonts, 200, 50);
$oPhpCaptcha->SetBackgroundImages('captcha.jpg');
$oPhpCaptcha->SetOwnerText($xoopsConfig['sitename']);
$oPhpCaptcha->Create();
?>

<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo para Solicitação de Recursos
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include '../../../include/cp_header.php';
if ( file_exists("language/".$xoopsConfig['language']."/modinfo.php") ) {
	include_once("../language/".$xoopsConfig['language']."/modinfo.php");
} else {
	include_once("../language/portuguesebr/modinfo.php");
}
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/funcoes.inc.php";
$op = (isset($_GET['op'])) ? $_GET['op'] : 'feature';
if (isset($_GET)) {
	foreach ($_GET as $k => $v) {
		$$k = $v;
	}
}

if (isset($_POST)) {
	foreach ($_POST as $k => $v) {
		$$k = $v;
	}
}
switch ($op) {
	case 'salvar':
		$yname = $_POST['yname'];
		$yemail = $_POST['yemail'];
		$ydomain = $_POST['ydomain'];
		$feedback_type = $_POST['feedback_type'];
		$feedback_other = $_POST['feedback_other'];
		$titulo = "Mastop Publish - FeedBack from ".$ydomain;
		$body = "<b>".$yname." (".$yemail.") - ".$ydomain."</b><br />";
		$body .= "Type: ".$feedback_type.((!empty($feedback_other)) ? " - ".$feedback_other : "")."<br />";
		$body .= prepareContent($_POST['feedback_content']);
		$xoopsMailer =& getMailer();
		$xoopsMailer->useMail();
		$xoopsMailer->setToEmails('publish@mastop.com.br');
		$xoopsMailer->setFromEmail($yemail);
		$xoopsMailer->setFromName($yname);
		$xoopsMailer->setSubject($titulo);
		$xoopsMailer->multimailer->IsHTML(true);
		$xoopsMailer->setBody($body);
		$xoopsMailer->send();
		$msg = '
			<div align="center" style="width: 80%; padding: 10px; padding-top:0px; padding-bottom: 5px; border: 2px solid #9C9C9C; background-color: #F2F2F2; margin-right:auto;margin-left:auto;">
			<h3>'.MPU_ADM_FEEDSUCCESS.'</h3>
			</div>
			';
	case 'feature':
	default:
		mpu_adm_menu();
		echo (!empty($msg)) ? $msg."<br />" : '';
		$form['titulo'] = MPU_ADM_FEEDBACKN;
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/feedback.form.inc.php";
		$feedbackform->display();
		break;
}
echo "<div align='center'><a href='http://www.mastop.com.br/produtos/publish/'><img src='images/footer.gif'></a><br /><a style='color: #029116; font-size:11px' href='feedback.php'>".MPU_ADM_FEEDBACK."</a> - <a style='color: #FF0000; font-size:11px' href='http://www.mastop.com.br/produtos/publish/checkversion.php?lang=".$xoopsConfig['language']."&version=".round($xoopsModule->getVar('version') / 100, 2)."' target='_blank'>".MPU_ADM_CHKVERSION."</a></div>";
xoops_cp_footer();
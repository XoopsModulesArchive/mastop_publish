<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Página para Recomendar a um Amigo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include '../../mainfile.php';
include_once "header.php";
if (!$_POST) {
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
			include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
			echo "<h2>".sprintf(MPU_MAI_RECTOAFRIEND, $mpu_classe->getVar("mpb_30_titulo"))."</h2>";
			$rec_form = new XoopsThemeForm("", 'rec_form', $_SERVER['PHP_SELF']);
			$rec_form->addElement(new XoopsFormText(MPU_MAI_YNAME, "yname", 20, 150), true);
			$rec_form->addElement(new XoopsFormText(MPU_MAI_YEMAIL, "yemail", 20, 150), true);
			$rec_form->addElement(new XoopsFormText(MPU_MAI_FNAME, "fname", 20, 150), true);
			$rec_form->addElement(new XoopsFormText(MPU_MAI_FEMAIL, "femail", 20, 150), true);
			$rec_form->addElement(new XoopsFormTextArea(MPU_MAI_MESSAGE, "message"), true);
			$rec_form->addElement(new XoopsFormHidden("tac", $mpu_classe->getVar("mpb_10_id")), true);
			$rec_form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
			$rec_form->display();
			include_once XOOPS_ROOT_PATH.'/footer.php';
		}
	}
}else{
	$tac = (isset($_POST['tac'])) ? $_POST['tac'] : 0;
	if(!$tac){
		redirect_header(XOOPS_URL, 2, MPU_MAI_404);
	}else{
		$mpu_classe = new mpu_mpb_mpublish($tac);
		if (!$mpu_classe->getVar("mpb_10_id")) {
			redirect_header(XOOPS_URL, 2, MPU_MAI_404);
		}else{
			$yname = $_POST['yname'];
			$yemail = $_POST['yemail'];
			$fname = $_POST['fname'];
			$femail = $_POST['femail'];
			$link = $mpu_classe->pegaLink();
			$titulo = $mpu_classe->getVar("mpb_30_titulo");
			$msg = nl2br(strip_tags($_POST['message']));
			$body = sprintf(MPU_MAI_MAILBODY, $fname, $yname, $yemail, $link, $titulo, $link, $yname, $msg);
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setToEmails($femail);
			$xoopsMailer->setFromEmail($yemail);
			$xoopsMailer->setFromName($yname);
			$xoopsMailer->setSubject(sprintf(MPU_MAI_MAILSUBJECT, $yname));
			$xoopsMailer->multimailer->IsHTML(true);
			$xoopsMailer->setBody($body);
			$xoopsMailer->send();
			echo'
			<div align="center" style="width: 80%; padding: 10px; padding-top:0px; padding-bottom: 5px; border: 2px solid #9C9C9C; background-color: #F2F2F2; margin-right:auto;margin-left:auto;">
			'.sprintf(MPU_MAI_MAILSUCCESS, $fname, $mpu_classe->pegaLink()).'
			</div>
			';
			include_once XOOPS_ROOT_PATH.'/footer.php';
		}
	}
}
?>
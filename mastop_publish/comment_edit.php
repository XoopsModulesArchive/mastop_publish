<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Editar Comentários
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include '../../mainfile.php';
if (!defined('XOOPS_ROOT_PATH') || !is_object($xoopsModule)) {
	exit();
}
include_once XOOPS_ROOT_PATH.'/include/comment_constants.php';
if ('system' != $xoopsModule->getVar('dirname') && XOOPS_COMMENT_APPROVENONE == $xoopsModuleConfig['com_rule']) {
	exit();
}
include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/comment.php';
$com_id = isset($_GET['com_id']) ? intval($_GET['com_id']) : 0;
$com_mode = isset($_GET['com_mode']) ? htmlspecialchars(trim($_GET['com_mode']), ENT_QUOTES) : '';
if ($com_mode == '') {
	if (is_object($xoopsUser)) {
		$com_mode = $xoopsUser->getVar('umode');
	} else {
		$com_mode = $xoopsConfig['com_mode'];
	}
}
if (!isset($_GET['com_order'])) {
	if (is_object($xoopsUser)) {
		$com_order = $xoopsUser->getVar('uorder');
	} else {
		$com_order = $xoopsConfig['com_order'];
	}
} else {
	$com_order = intval($_GET['com_order']);
}
$comment_handler =& xoops_gethandler('comment');
$comment =& $comment_handler->get($com_id);
$dohtml = $comment->getVar('dohtml');
$dosmiley = $comment->getVar('dosmiley');
$dobr = $comment->getVar('dobr');
$doxcode = $comment->getVar('doxcode');
$com_icon = $comment->getVar('com_icon');
$com_itemid = $comment->getVar('com_itemid');
$com_title = $comment->getVar('com_title', 'E');
$com_text = $comment->getVar('com_text', 'E');
$com_pid = $comment->getVar('com_pid');
$com_status = $comment->getVar('com_status');
$com_rootid = $comment->getVar('com_rootid');
if ($xoopsModule->getVar('dirname') != 'system') {
	include XOOPS_ROOT_PATH.'/header.php';
	include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/comment_form.php";
	include XOOPS_ROOT_PATH.'/footer.php';
} else {
	xoops_cp_header();
	include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/comment_form.php";
	xoops_cp_footer();
}
?>
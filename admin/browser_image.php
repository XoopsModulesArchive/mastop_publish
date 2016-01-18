<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo responsável pela integração da Biblioteca de imagens
### do Xoops com o TinyMCE
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once '../../../mainfile.php';
include_once XOOPS_ROOT_PATH . "/include/cp_functions.php";
$moduleperm_handler = & xoops_gethandler( 'groupperm' );
$url_arr = explode('/',strstr($xoopsRequestUri,'/modules/'));
$module_handler =& xoops_gethandler('module');
$MPublishModule =& $module_handler->getByDirname($url_arr[2]);
unset($url_arr);

if ( $MPublishModule->getVar( 'hasconfig' ) == 1 || $MPublishModule->getVar( 'hascomments' ) == 1 ) {
	$config_handler = & xoops_gethandler( 'config' );
	$MPublishModuleConfig = & $config_handler->getConfigsByCat( 0, $MPublishModule->getVar( 'mid' ) );
}

if ( file_exists( "../language/" . $xoopsConfig['language'] . "/admin.php" ) ) {
	include_once "../language/" . $xoopsConfig['language'] . "/admin.php";
}
elseif ( file_exists( "../language/portuguesebr/admin.php" ) ) {
	include_once "../language/portuguesebr/admin.php";
}

if ( file_exists("../language/".$xoopsConfig['language']."/modinfo.php") ) {
	include_once("../language/".$xoopsConfig['language']."/modinfo.php");
} else {
	include_once("../language/portuguesebr/modinfo.php");
}
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
$op = (empty($_GET['op'])) ? 'list' : $_GET['op'];
$op = (empty($_POST['op'])) ? $op : $_POST['op'];
if (!is_object($xoopsUser)) {
	$groups = array(XOOPS_GROUP_ANONYMOUS);
	$admin = false;
} else {
	$groups =& $xoopsUser->getGroups();
	$admin = (!$xoopsUser->isAdmin(1)) ? false : true;
}
$imgcat_handler = xoops_gethandler('imagecategory');
$criteriaRead = new CriteriaCompo();
if (is_array($groups) && !empty($groups)) {
	$criteriaTray = new CriteriaCompo();
	foreach ($groups as $gid) {
		$criteriaTray->add(new Criteria('gperm_groupid', $gid), 'OR');
	}
	$criteriaRead->add($criteriaTray);
	$criteriaRead->add(new Criteria('gperm_name', 'imgcat_read'));
	$criteriaRead->add(new Criteria('gperm_modid', 1));
}
$criteriaRead->add(new Criteria('imgcat_display', 1));
$imagecategorys =& $imgcat_handler->getObjects($criteriaRead);
$criteriaWrite = new CriteriaCompo();
if (is_array($groups) && !empty($groups)) {
	$criteriaWrite->add($criteriaTray);
	$criteriaWrite->add(new Criteria('gperm_name', 'imgcat_read'));
	$criteriaWrite->add(new Criteria('gperm_modid', 1));
}
$criteriaWrite->add(new Criteria('imgcat_display', 1));
$imagecategorysWrite =& $imgcat_handler->getObjects($criteriaWrite);
$readCount = count($imagecategorys);
$writeCount = count($imagecategorysWrite);
$mpb_wysiwyg_url = XOOPS_URL.$MPublishModuleConfig['mpu_conf_wysiwyg_path'];
include_once(XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/images.php");
if ($op == 'updatecat' && $admin) {
	$imgcat_id = $_POST['imgcat_id'];
	$readgroup = $_POST['readgroup'];
	$writegroup = $_POST['writegroup'];
	if (!$GLOBALS['xoopsSecurity']->check() || $imgcat_id <= 0) {
		redirect_header($_SERVER['PHP_SELF'],1, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$imagecategory =& $imgcat_handler->get($imgcat_id);
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$imagecategory->setVar('imgcat_name', $_POST['imgcat_name']);
	$imgcat_display = empty($_POST['imgcat_display']) ? 0 : 1;
	$imagecategory->setVar('imgcat_display', $_POST['imgcat_display']);
	$imagecategory->setVar('imgcat_maxsize', $_POST['imgcat_maxsize']);
	$imagecategory->setVar('imgcat_maxwidth', $_POST['imgcat_maxwidth']);
	$imagecategory->setVar('imgcat_maxheight', $_POST['imgcat_maxheight']);
	$imagecategory->setVar('imgcat_weight', $_POST['imgcat_weight']);
	if (!$imgcat_handler->insert($imagecategory)) {
		exit();
	}
	$imagecategoryperm_handler =& xoops_gethandler('groupperm');
	$criteria = new CriteriaCompo(new Criteria('gperm_itemid', $imgcat_id));
	$criteria->add(new Criteria('gperm_modid', 1));
	$criteria2 = new CriteriaCompo(new Criteria('gperm_name', 'imgcat_write'));
	$criteria2->add(new Criteria('gperm_name', 'imgcat_read'), 'OR');
	$criteria->add($criteria2);
	$imagecategoryperm_handler->deleteAll($criteria);
	if (!isset($readgroup)) {
		$readgroup = array();
	}
	if (!in_array(XOOPS_GROUP_ADMIN, $readgroup)) {
		array_push($readgroup, XOOPS_GROUP_ADMIN);
	}
	foreach ($readgroup as $rgroup) {
		$imagecategoryperm =& $imagecategoryperm_handler->create();
		$imagecategoryperm->setVar('gperm_groupid', $rgroup);
		$imagecategoryperm->setVar('gperm_itemid', $imgcat_id);
		$imagecategoryperm->setVar('gperm_name', 'imgcat_read');
		$imagecategoryperm->setVar('gperm_modid', 1);
		$imagecategoryperm_handler->insert($imagecategoryperm);
		unset($imagecategoryperm);
	}
	if (!isset($writegroup)) {
		$writegroup = array();
	}
	if (!in_array(XOOPS_GROUP_ADMIN, $writegroup)) {
		array_push($writegroup, XOOPS_GROUP_ADMIN);
	}
	foreach ($writegroup as $wgroup) {
		$imagecategoryperm =& $imagecategoryperm_handler->create();
		$imagecategoryperm->setVar('gperm_groupid', $wgroup);
		$imagecategoryperm->setVar('gperm_itemid', $imgcat_id);
		$imagecategoryperm->setVar('gperm_name', 'imgcat_write');
		$imagecategoryperm->setVar('gperm_modid', 1);
		$imagecategoryperm_handler->insert($imagecategoryperm);
		unset($imagecategoryperm);
	}
	$op = "list";
}
if ($op == 'addcat' && $admin) {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header($_SERVER['PHP_SELF'],2, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$readgroup = $_POST['readgroup'];
	$writegroup = $_POST['writegroup'];
	$imagecategory =& $imgcat_handler->create();
	$imagecategory->setVar('imgcat_name', $_POST['imgcat_name']);
	$imagecategory->setVar('imgcat_maxsize', $_POST['imgcat_maxsize']);
	$imagecategory->setVar('imgcat_maxwidth', $_POST['imgcat_maxwidth']);
	$imagecategory->setVar('imgcat_maxheight', $_POST['imgcat_maxheight']);
	$imgcat_display = empty($_POST['imgcat_display']) ? 0 : 1;
	$imagecategory->setVar('imgcat_display', $imgcat_display);
	$imagecategory->setVar('imgcat_weight', $_POST['imgcat_weight']);
	$imagecategory->setVar('imgcat_storetype', $_POST['imgcat_storetype']);
	$imagecategory->setVar('imgcat_type', 'C');
	if (!$imgcat_handler->insert($imagecategory)) {
		exit();
	}
	$newid = $imagecategory->getVar('imgcat_id');
	$imagecategoryperm_handler =& xoops_gethandler('groupperm');
	if (!isset($readgroup)) {
		$readgroup = array();
	}
	if (!in_array(XOOPS_GROUP_ADMIN, $readgroup)) {
		array_push($readgroup, XOOPS_GROUP_ADMIN);
	}
	foreach ($readgroup as $rgroup) {
		$imagecategoryperm =& $imagecategoryperm_handler->create();
		$imagecategoryperm->setVar('gperm_groupid', $rgroup);
		$imagecategoryperm->setVar('gperm_itemid', $newid);
		$imagecategoryperm->setVar('gperm_name', 'imgcat_read');
		$imagecategoryperm->setVar('gperm_modid', 1);
		$imagecategoryperm_handler->insert($imagecategoryperm);
		unset($imagecategoryperm);
	}
	if (!isset($writegroup)) {
		$writegroup = array();
	}
	if (!in_array(XOOPS_GROUP_ADMIN, $writegroup)) {
		array_push($writegroup, XOOPS_GROUP_ADMIN);
	}
	foreach ($writegroup as $wgroup) {
		$imagecategoryperm =& $imagecategoryperm_handler->create();
		$imagecategoryperm->setVar('gperm_groupid', $wgroup);
		$imagecategoryperm->setVar('gperm_itemid', $newid);
		$imagecategoryperm->setVar('gperm_name', 'imgcat_write');
		$imagecategoryperm->setVar('gperm_modid', 1);
		$imagecategoryperm_handler->insert($imagecategoryperm);
		unset($imagecategoryperm);
	}
	$op = "list";
}
if ($op == 'delcatok' && $admin) {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header($_SERVER['PHP_SELF'],3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$imgcat_id = intval($_POST['imgcat_id']);
	if ($imgcat_id <= 0) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$imagecategory =& $imgcat_handler->get($imgcat_id);
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$image_handler =& xoops_gethandler('image');
	$images =& $image_handler->getObjects(new Criteria('imgcat_id', $imgcat_id), true, false);
	$errors = array();
	foreach (array_keys($images) as $i) {
		$image_handler->delete($images[$i]);
		if (file_exists(XOOPS_UPLOAD_PATH.'/'.$images[$i]->getVar('image_name'))){
			@unlink(XOOPS_UPLOAD_PATH.'/'.$images[$i]->getVar('image_name'));
		}
	}
	$imgcat_handler->delete($imagecategory);
	$op = "list";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$lang_browser_procurar}</title>
<script language="javascript" type="text/javascript" src="<?=$mpb_wysiwyg_url?>/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="<?=$mpb_wysiwyg_url?>/utils/mctabs.js"></script>
<script language="javascript" type="text/javascript">
<!--
function addItem(itemurl, nome, desc) {
	var campo = tinyMCE.getWindowArg('campo');
	var win = tinyMCE.getWindowArg('win');
	win.document.getElementById(campo).value=itemurl;
	if(campo == "src"){
		if(win.document.getElementById('title')) win.document.getElementById('title').value=nome;
		if(win.document.getElementById('alt')) win.document.getElementById('alt').value=desc;
		if(win.showPreviewImage) win.showPreviewImage(itemurl);
	}
	tinyMCEPopup.close();
	return;
}
function init() {
	if (tinyMCE.isMSIE){
		tinyMCEPopup.resizeToInnerSize();
	}
	window.focus();
}

function cancelAction() {
	top.close();
}

//-->
</script>
	<base target="_self" />
</head>
<body onload="tinyMCEPopup.executeOnLoad('init();');">
		<div class="tabs">
			<ul>
				<?php if ($readCount > 0) {?><li id="gerenciador_tab" <?php echo ($op == "listimg" || $op == "editcat" || $op == "delcat" || $op == "list") ? ' class="current"' : '';?>><span><a href="javascript:mcTabs.displayTab('gerenciador_tab','gerenciador_panel');" onmousedown="return false;">{$lang_browser_ger_imagens}</a></span></li><?php } ?>
				<?php if ($writeCount > 0) {?><li id="nova_imagem_tab" <?php echo ($op == "addfile") ? ' class="current"' : '';?>><span><a href="javascript:mcTabs.displayTab('nova_imagem_tab','nova_imagem_panel');" onmousedown="return false;">{$lang_browser_nova_imagem}</a></span></li><?php } ?>
				<?php if ($admin) {?><li id="nova_cat_tab" <?php echo ($op == "addcat" || $readCount <= 0) ? ' class="current"' : '';?>><span><a href="javascript:mcTabs.displayTab('nova_cat_tab','nova_cat_panel');" onmousedown="return false;">{$lang_browser_nova_cat}</a></span></li><?php } ?>
			</ul>
		</div>
<div class="panel_wrapper">
			<?php if ($readCount > 0) {?>
			<div id="gerenciador_panel" class="panel <?php echo ($op == "listimg" || $op == "editcat" || $op == "delcat" || $op == "list") ? 'current' : '';?>" style="overflow: auto;">
				<h3>{$lang_browser_gimg_title}</h3>
<?PHP
if ($op == 'delcat' && $admin) {
	xoops_confirm(array('op' => 'delcatok', 'imgcat_id' => $_GET['imgcat_id']), $_SERVER['PHP_SELF'], MPU_ADM_CONFIRMA_DEL_CATIMG);
}elseif ($op == 'editcat' && $admin) {
	$imgcat_id = $_GET['imgcat_id'];
	if ($imgcat_id <= 0) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$imagecategory =& $imgcat_handler->get($imgcat_id);
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$imagecategoryperm_handler =& xoops_gethandler('groupperm');
	$form = new XoopsThemeForm(_MD_EDITIMGCAT, 'imagecat_form', $_SERVER['PHP_SELF'], 'post', true);
	$form->addElement(new XoopsFormText(_MD_IMGCATNAME, 'imgcat_name', 50, 255, $imagecategory->getVar('imgcat_name')), true);
	$form->addElement(new XoopsFormSelectGroup(_MD_IMGCATRGRP, 'readgroup', true, $imagecategoryperm_handler->getGroupIds('imgcat_read', $imgcat_id), 5, true));
	$form->addElement(new XoopsFormSelectGroup(_MD_IMGCATWGRP, 'writegroup', true, $imagecategoryperm_handler->getGroupIds('imgcat_write', $imgcat_id), 5, true));
	$form->addElement(new XoopsFormText(_IMGMAXSIZE, 'imgcat_maxsize', 10, 10, $imagecategory->getVar('imgcat_maxsize')));
	$form->addElement(new XoopsFormText(_IMGMAXWIDTH, 'imgcat_maxwidth', 3, 4, $imagecategory->getVar('imgcat_maxwidth')));
	$form->addElement(new XoopsFormText(_IMGMAXHEIGHT, 'imgcat_maxheight', 3, 4, $imagecategory->getVar('imgcat_maxheight')));
	$form->addElement(new XoopsFormText(_MD_IMGCATWEIGHT, 'imgcat_weight', 3, 4, $imagecategory->getVar('imgcat_weight')));
	$form->addElement(new XoopsFormRadioYN(_MD_IMGCATDISPLAY, 'imgcat_display', $imagecategory->getVar('imgcat_display'), _YES, _NO));
	$storetype = array('db' => _MD_INDB, 'file' => _MD_ASFILE);
	$form->addElement(new XoopsFormLabel(_MD_IMGCATSTRTYPE, $storetype[$imagecategory->getVar('imgcat_storetype')]));
	$form->addElement(new XoopsFormHidden('imgcat_id', $imgcat_id));
	$form->addElement(new XoopsFormHidden('op', 'updatecat'));
	$form->addElement(new XoopsFormButton('', 'imgcat_button', _SUBMIT, 'submit'));
	echo '<a href="'.$_SERVER['PHP_SELF'].'">'. _MD_IMGMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$imagecategory->getVar('imgcat_name').'<br /><br />';
	$form->display();
}elseif ($op == 'listimg') {
	$imgcat_id = intval($_GET['imgcat_id']);
	$imagecategory =& $imgcat_handler->get($imgcat_id);
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	$image_handler = xoops_gethandler('image');
	echo '<h4><a href="'.$_SERVER['PHP_SELF'].'">'. _MD_IMGMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$imagecategory->getVar('imgcat_name').'</h4><br /><br />';
	$criteria = new Criteria('imgcat_id', $imgcat_id);
	$imgcount = $image_handler->getCount($criteria);
	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
	$criteria->setStart($start);
	$criteria->setLimit(20);
	$images =& $image_handler->getObjects($criteria, true, false);
	echo '<table style="width:100%;"><thead><tr>
	<td>&nbsp;</td>
	<td style="border: 1px double black; text-align: center">'._IMAGENAME.'</td>
	<td style="border: 1px double black; text-align: center">'._IMAGEMIME.'</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_ACAO.'</td>
	</tr></thead><tbody>
	';
	foreach (array_keys($images) as $i) {
		echo '<tr><td width="30%" style="text-align: center">';
		if ($imagecategory->getVar('imgcat_storetype') == 'db') {
			$imagem_url = XOOPS_URL.'/image.php?id='.$i;
		} else {
			$imagem_url = XOOPS_UPLOAD_URL.'/'.$images[$i]->getVar('image_name');
		}
		echo '<img src="'.$imagem_url.'" alt="" width="50" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid white\'" style="border:2px solid white" onclick="addItem(\''.$imagem_url.'\', \''.$images[$i]->getVar('image_nicename').'\', \''.$images[$i]->getVar('image_nicename').'\')"/>';
		echo '</td><td style="border: 2px double #F0F0EE; text-align: center">'.$images[$i]->getVar('image_nicename').'</td><td style="border: 2px double #F0F0EE; text-align: center">'.$images[$i]->getVar('image_mimetype').'</td>';
		echo '<td style="border: 2px double #F0F0EE; text-align: center"><a href="javascript:void(0)" onclick="addItem(\''.$imagem_url.'\', \''.$images[$i]->getVar('image_nicename').'\', \''.$images[$i]->getVar('image_nicename').'\')">'._SELECT.'</a></td></tr>';
	}
	echo "</tbody></table>";
	if ($imgcount > 0) {
		if ($imgcount > 20) {
			include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
			$nav = new XoopsPageNav($imgcount, 20, $start, 'start', 'op=listimg&amp;imgcat_id='.$imgcat_id);
			echo '<div style="text-align:right">'.$nav->renderNav().'</div>';
		}
	}
}else{
	echo '<ul>';
	$catcount = count($imagecategorys);
	$image_handler =& xoops_gethandler('image');
	for ($i = 0; $i < $catcount; $i++) {
		$count = $image_handler->getCount(new Criteria('imgcat_id', $imagecategorys[$i]->getVar('imgcat_id')));
		echo '<li>'.$imagecategorys[$i]->getVar('imgcat_name').' ('.sprintf(_NUMIMAGES, '<b>'.$count.'</b>').')';
		echo ($count > 0) ? ' [<a href="'.$_SERVER['PHP_SELF'].'?op=listimg&amp;imgcat_id='.$imagecategorys[$i]->getVar('imgcat_id').'">'._LIST.'</a>]' : '';
		echo ($admin) ? ' [<a href="'.$_SERVER['PHP_SELF'].'?op=editcat&amp;imgcat_id='.$imagecategorys[$i]->getVar('imgcat_id').'">'._EDIT.'</a>]' : '';
		if ($imagecategorys[$i]->getVar('imgcat_type') == 'C' && $admin) {
			echo ' [<a href="'.$_SERVER['PHP_SELF'].'?op=delcat&amp;imgcat_id='.$imagecategorys[$i]->getVar('imgcat_id').'">'._DELETE.'</a>]';
		}
		echo '</li>';
	}
	echo '</ul>';
}
?>
</div><?php } ?>
<?php if ($admin) {?>
			<div id="nova_cat_panel" class="panel <?php echo ($op == "addcat" || $readCount <= 0) ? 'current' : '';?>" style="overflow: auto;">
				<div id="nova_catcontainer">
					<h3><?=_MD_ADDIMGCAT;?></h3>
<?PHP
$form = new XoopsThemeForm("", 'imagecat_form', $_SERVER['PHP_SELF'], 'post', true);
$form->addElement(new XoopsFormText(_MD_IMGCATNAME, 'imgcat_name', 50, 255), true);
$form->addElement(new XoopsFormSelectGroup(_MD_IMGCATRGRP, 'readgroup', true, XOOPS_GROUP_ADMIN, 5, true));
$form->addElement(new XoopsFormSelectGroup(_MD_IMGCATWGRP, 'writegroup', true, XOOPS_GROUP_ADMIN, 5, true));
$form->addElement(new XoopsFormText(_IMGMAXSIZE, 'imgcat_maxsize', 10, 10, 50000));
$form->addElement(new XoopsFormText(_IMGMAXWIDTH, 'imgcat_maxwidth', 3, 4, 120));
$form->addElement(new XoopsFormText(_IMGMAXHEIGHT, 'imgcat_maxheight', 3, 4, 120));
$form->addElement(new XoopsFormText(_MD_IMGCATWEIGHT, 'imgcat_weight', 3, 4, 0));
$form->addElement(new XoopsFormRadioYN(_MD_IMGCATDISPLAY, 'imgcat_display', 1, _YES, _NO));
$storetype = new XoopsFormRadio(_MD_IMGCATSTRTYPE.'<br /><span style="color:#ff0000;">'._MD_STRTYOPENG.'</span>', 'imgcat_storetype', 'file');
$storetype->addOptionArray(array('file' => _MD_ASFILE, 'db' => _MD_INDB));
$form->addElement($storetype);
$form->addElement(new XoopsFormHidden('op', 'addcat'));
$form->addElement(new XoopsFormButton('', 'imgcat_button', _SUBMIT, 'submit'));
$form->display();
?>
					<p>&nbsp;</p>
				</div>
			</div>
<?php } ?>
<?php if ($writeCount > 0) {?>
			<div id="nova_imagem_panel" class="panel <?php echo ($op == "addfile") ? 'current' : '';?>" style="overflow: visible;">
<?PHP
if ($op == 'addfile') {
	if (!$GLOBALS['xoopsSecurity']->check()) {
		redirect_header($_SERVER['PHP_SELF'], 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
	}
	$imagecategory =& $imgcat_handler->get(intval($_POST['imgcat_id']));
	if (!is_object($imagecategory)) {
		redirect_header($_SERVER['PHP_SELF'],1);
	}
	include_once XOOPS_ROOT_PATH.'/class/uploader.php';
	$uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/bmp'), $imagecategory->getVar('imgcat_maxsize'), $imagecategory->getVar('imgcat_maxwidth'), $imagecategory->getVar('imgcat_maxheight'));
	$uploader->setPrefix('img');
	$err = array();
	$ucount = count($_POST['xoops_upload_file']);
	for ($i = 0; $i < $ucount; $i++) {
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][$i])) {
			if (!$uploader->upload()) {
				$err[] = $uploader->getErrors();
			} else {
				$image_handler =& xoops_gethandler('image');
				$image =& $image_handler->create();
				$image->setVar('image_name', $uploader->getSavedFileName());
				$image->setVar('image_nicename', $_POST['image_nicename']);
				$image->setVar('image_mimetype', $uploader->getMediaType());
				$image->setVar('image_created', time());
				$image_display = empty($_POST['image_display']) ? 0 : 1;
				$image->setVar('image_display', $_POST['image_display']);
				$image->setVar('image_weight', $_POST['image_weight']);
				$image->setVar('imgcat_id', $_POST['imgcat_id']);
				if ($imagecategory->getVar('imgcat_storetype') == 'db') {
					$fp = @fopen($uploader->getSavedDestination(), 'rb');
					$fbinary = @fread($fp, filesize($uploader->getSavedDestination()));
					@fclose($fp);
					$image->setVar('image_body', $fbinary, true);
					@unlink($uploader->getSavedDestination());
				}
				if (!$image_handler->insert($image)) {
					$err[] = sprintf(_FAILSAVEIMG, $image->getVar('image_nicename'));
				}
			}
		} else {
			$err[] = sprintf(_FAILFETCHIMG, $i);
			$err = array_merge($err, $uploader->getErrors(false));
		}
	}
	if (count($err) > 0) {
		echo "<fieldset><legend>"._ERRORS."</legend>";
		xoops_error($err);
		echo "</fieldset>";
	}else{
		echo "<fieldset><legend>".MPU_ADM_SUCESS1."</legend>";
		echo '<table style="width:100%;"><thead><tr>
	<td>&nbsp;</td>
	<td style="border: 1px double black; text-align: center">'._IMAGENAME.'</td>
	<td style="border: 1px double black; text-align: center">'._IMAGEMIME.'</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_ACAO.'</td>
	</tr></thead><tbody>
	';
		echo '<tr><td width="30%" style="text-align: center">';
		if ($imagecategory->getVar('imgcat_storetype') == 'db') {
			$imagem_url = XOOPS_URL.'/image.php?id='.$image->getVar('image_id');
		} else {
			$imagem_url = XOOPS_UPLOAD_URL.'/'.$image->getVar('image_name');
		}
		echo '<img src="'.$imagem_url.'" alt="" width="50" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid white\'" style="border:2px solid white" onclick="addItem(\''.$imagem_url.'\', \''.$image->getVar('image_nicename').'\', \''.$image->getVar('image_nicename').'\')"/>';
		echo '</td><td style="border: 2px double #F0F0EE; text-align: center">'.$image->getVar('image_nicename').'</td><td style="border: 2px double #F0F0EE; text-align: center">'.$image->getVar('image_mimetype').'</td>';
		echo '<td style="border: 2px double #F0F0EE; text-align: center"><a href="javascript:void(0)" onclick="addItem(\''.$imagem_url.'\', \''.$image->getVar('image_nicename').'\', \''.$image->getVar('image_nicename').'\')">'._SELECT.'</a></td></tr>';
	}
	echo "</tbody></table></fieldset>";
}
echo '<h3>'._ADDIMAGE.'</h3>';
$catcount = count($imagecategorysWrite);
if (!empty($catcount)) {
	$form = new XoopsThemeForm("", 'image_form', $_SERVER['PHP_SELF'], 'post', true);
	$form->setExtra('enctype="multipart/form-data"');
	$form->addElement(new XoopsFormText(_IMAGENAME, 'image_nicename', 50, 255), true);
	$select = new XoopsFormSelect(_IMAGECAT, 'imgcat_id');
	$select->addOptionArray($imgcat_handler->getList($groups, "imgcat_write", 1));
	$form->addElement($select, true);
	$form->addElement(new XoopsFormFile(_IMAGEFILE, 'image_file', 5000000));
	$form->addElement(new XoopsFormText(_IMGWEIGHT, 'image_weight', 3, 4, 0));
	$form->addElement(new XoopsFormRadioYN(_IMGDISPLAY, 'image_display', 1, _YES, _NO));
	$form->addElement(new XoopsFormHidden('op', 'addfile'));
	$form->addElement(new XoopsFormButton('', 'img_button', _SUBMIT, 'submit'));
	$form->display();
}else{
	echo MPU_ADM_BROWSER_IMGERRO_NOCAT;
}
?>
			</div>
<?php } ?>
		</div>
		<div class="mceActionPanel">
			<div style="float: right">
				<input type="button" id="cancel" name="cancel" value="{$lang_close}" onclick="tinyMCEPopup.close();" />
			</div>
		</div>
<!--
<!--{xo-logger-output}-->
-->
</body>
</html>
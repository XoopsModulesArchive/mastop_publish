<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo que gera a lista de imagens para exibir no Option
### List do TinyMCE
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once("../../../mainfile.php");
$xoopsLogger->activated = false;
$ret = "";
if (!is_object($xoopsUser)) {
	$group = array(XOOPS_GROUP_ANONYMOUS);
} else {
	$group =& $xoopsUser->getGroups();
}
$imgcat_handler =& xoops_gethandler('imagecategory');
$catlist =& $imgcat_handler->getList($group, 'imgcat_read', 1);
$catcount = count($catlist);
if ($catcount > 0) {
	foreach ($catlist as $c_id => $c_name) {
		$ret .= '["--- '.$c_name.' ---", ""],';
		$image_handler = xoops_gethandler('image');
		$criteria = new CriteriaCompo(new Criteria('imgcat_id', $c_id));
		$criteria->add(new Criteria('image_display', 1));
		$total = $image_handler->getCount($criteria);
		if ($total > 0) {
			$imgcat =& $imgcat_handler->get($c_id);
			$storetype = $imgcat->getVar('imgcat_storetype');
			if ($storetype == 'db') {
				$images =& $image_handler->getObjects($criteria, false, true);
			} else {
				$images =& $image_handler->getObjects($criteria, false, false);
			}
			$imgcount = count($images);
			for ($i = 0; $i < $imgcount; $i++) {
				if ($storetype == 'db') {
					$ret .= '["'.$images[$i]->getVar('image_nicename').'", "'.XOOPS_URL."/image.php?id=".$images[$i]->getVar('image_id').'"],';
				} else {
					$ret .= '["'.$images[$i]->getVar('image_nicename').'", "'.XOOPS_UPLOAD_URL.'/'.$images[$i]->getVar('image_name').'"],';
				}
			}
		}
	}
	$ret = substr($ret, 0, -1);
}
?>
var tinyMCEImageList = new Array(
	<?=$ret;?>
);
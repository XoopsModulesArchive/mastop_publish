<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo responsável pela integração do Cadastro de Arquivos
### com o TinyMCE
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================

include_once("admin_header.php");
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
$op = (empty($_GET['op'])) ? 'list' : $_GET['op'];
$op = (empty($_POST['op'])) ? $op : $_POST['op'];
$mpb_wysiwyg_url = XOOPS_URL.$xoopsModuleConfig['mpu_conf_wysiwyg_path'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$lang_browser_procurar}</title>
<script language="javascript" type="text/javascript" src="<?=$mpb_wysiwyg_url?>/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="<?=$mpb_wysiwyg_url?>/utils/mctabs.js"></script>
<script language="javascript" type="text/javascript">
<!--
function addItem(itemurl, nome) {
	var campo = tinyMCE.getWindowArg('campo');
	var win = tinyMCE.getWindowArg('win');
	win.document.getElementById(campo).value=itemurl;
	if(campo == "href" && win.document.getElementById('title')) win.document.getElementById('title').value=nome;
	if(campo == "href"){
		if(win.selectByValue) win.selectByValue(win.document.forms[0],'linklisthref',itemurl);
	}
	tinyMCEPopup.close();
	return;
}
function init() {
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
				<li id="gerenciador_tab" <?php echo ($op == "listfil" || $op == "list") ? ' class="current"' : '';?>><span><a href="javascript:mcTabs.displayTab('gerenciador_tab','gerenciador_panel');" onmousedown="return false;">{$lang_browser_ger_files}</a></span></li>
				<li id="novo_file_tab" <?php echo ($op == "addfile") ? ' class="current"' : '';?>><span><a href="javascript:mcTabs.displayTab('novo_file_tab','novo_file_panel');" onmousedown="return false;">{$lang_browser_novo_file}</a></span></li>
			</ul>
		</div>
<div class="panel_wrapper">
			<div id="gerenciador_panel" class="panel <?php echo ($op == "listfil" || $op == "list") ? 'current' : '';?>" style="overflow: auto;">
				<h3>{$lang_browser_file_title}</h3>
<?PHP
if ($op == 'listfil') {
	$fil_30_mime = $_GET['fil_30_mime'];
	$fil_classe = new mpu_fil_files();
	$criterio = new CriteriaCompo(new Criteria("fil_30_mime", $fil_30_mime));
	if(!empty($_GET['fil_30_nome'])){
		$criterio->add(new Criteria("fil_30_nome", "%".$_GET['fil_30_nome']."%", "LIKE"));
	}
	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
	$criterio->setStart($start);
	$criterio->setLimit(20);
	$files = $fil_classe->PegaTudo($criterio);
	$files_total = $fil_classe->contar($criterio);
	if (!$files) {
		header("Location: ".$_SERVER['PHP_SELF']."?fil_30_mime=".$fil_30_mime."&erro=".urlencode(MPU_ADM_ERRO_FIL404).((!empty($_GET['fil_30_nome'])) ? "&fil_30_nome=".urlencode($_GET['fil_30_nome']) : ""));
	}else{
		$tipos_select = "";
		$mimes = $fil_classe->pegaMimes();
		foreach ($mimes as $k=>$v) {
			$tipos_select .= "<option value='".$k."' ".(($fil_30_mime == $k) ? "selected='selected'" : "").">".$v."</option>";
		}
		echo '<h4><a href="'.$_SERVER['PHP_SELF'].'">'. MPU_ADM_BROWSER_GER_FIL .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$mimes[$fil_30_mime].'</h4>';
		echo "<fieldset><legend>".MPU_ADM_FILTROS."</legend>";
		echo "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
		echo MPU_ADM_FIL_30_NOME." <input type='hidden' name='op' value='listfil'> <input type='text' name='fil_30_nome' value='".((!empty($_GET['fil_30_nome'])) ? $_GET['fil_30_nome'] : "")."'> ".MPU_ADM_FIL_30_MIME." <select name='fil_30_mime'>".$tipos_select."</select> <input type='image' src='".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/images/envia.gif' align='absmiddle' style='border:0'>";
		echo "</form></fieldset><br />";
		echo '<table style="width:100%;"><thead><tr>
	<td>&nbsp;</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_FIL_30_NOME.'</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_FIL_10_TAMANHO.'</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_FIL_22_DATA.'</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_ACAO.'</td>
	</tr></thead><tbody>
	';
		foreach ($files as $fil) {
			$file_url = MPU_FILES_URL."/".$fil->getVar('fil_30_arquivo');
			echo '<tr><td width="30%" style="text-align: center">';
			echo '<a href="javascript:void(0)" style="border:2px solid white" onclick="addItem(\''.$file_url.'\', \''.$fil->getVar('fil_30_nome').'\')"/>'.$fil->getVar('fil_10_id').'</a>';
			echo '</td><td style="border: 2px double #F0F0EE; text-align: center">'.$fil->getVar('fil_30_nome').'</td><td style="border: 2px double #F0F0EE; text-align: center">'.number_format($fil->getVar("fil_10_tamanho")/1024, 2, ".", "").' Kb</td><td style="border: 2px double #F0F0EE; text-align: center">'.date("d/m/Y",$fil->getVar("fil_22_data")).'</td>';
			echo '<td style="border: 2px double #F0F0EE; text-align: center"><a href="javascript:void(0)" onclick="addItem(\''.$file_url.'\', \''.$fil->getVar('fil_30_nome').'\')">'._SELECT.'</a></td></tr>';
		}
		echo "</tbody></table>";
		if ($files_total > 0) {
			if ($files_total > 20) {
				include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
				$nav = new XoopsPageNav($files_total, 20, $start, 'start', 'op=listfil&amp;fil_30_mime='.$fil_30_mime.((!empty($_GET['fil_30_nome'])) ? "&amp;fil_30_nome=".$_GET['fil_30_nome'] : ""));
				echo '<div style="text-align:right">'.$nav->renderNav().'</div>';
			}
		}
	}
}else{
	$fil_classe = new mpu_fil_files();
	$mimes = $fil_classe->pegaMimes();
	$tipos_select = "";
	if($mimes){
		echo '<ul>';
		foreach ($mimes as $k=>$v) {
			$tipos_select .= "<option value='".$k."' ".((!empty($_GET['fil_30_mime']) && $_GET['fil_30_mime'] == $k) ? "selected='selected'" : "").">".$v."</option>";
			$files_count = $fil_classe->contar(new Criteria('fil_30_mime', $k));
			echo '<li>'.$v.' (<b>'.$files_count.'</b> '.MPU_ADM_BROWSER_GER_FIL.') '.(($files_count > 0) ? '[<a href="'.$_SERVER['PHP_SELF'].'?op=listfil&amp;fil_30_mime='.$k.'">'._LIST.'</a>]</li>' : '');
		}
		echo '</ul>';
		echo "<fieldset><legend>".MPU_ADM_FILTROS."</legend>";
		echo "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
		echo MPU_ADM_FIL_30_NOME." <input type='hidden' name='op' value='listfil'> <input type='text' name='fil_30_nome' value='".((!empty($_GET['fil_30_nome'])) ? $_GET['fil_30_nome'] : "")."'> ".MPU_ADM_FIL_30_MIME." <select name='fil_30_mime'>".$tipos_select."</select> <input type='image' src='".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/images/envia.gif' align='absmiddle' style='border:0'>";
		echo "</form></fieldset>";
	}
	if (!empty($_GET['erro'])) {
		echo "<br /><div style='border: 2px solid red; text-align:center; font-weight:bold'>".$_GET['erro']."</div>";
	}
}
?>
</div>

			<div id="novo_file_panel" class="panel <?php echo ($op == "addfile") ? 'current' : '';?>" style="overflow: visible;">
<?PHP
if ($op == 'addfile') {
	$file = new mpu_fil_files();
	$file_nome = $_FILES[$_POST['xoops_upload_file'][0]];
	$file_nome = (get_magic_quotes_gpc()) ? stripslashes($file_nome['name']) : $file_nome['name'];
	if(xoops_trim($file_nome!='')) {
		include_once(XOOPS_ROOT_PATH."/class/uploader.php");
		$uploader = new XoopsMediaUploader( MPU_FILES_PATH, $xoopsModuleConfig['mpu_conf_mimetypes'], $xoopsModuleConfig['mpu_mmax_filesize']*1024);
		$uploader->setPrefix("files_");
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if ($uploader->upload()) {
				$file->setVar("fil_30_nome", $_POST['fil_30_nome']);
				$file->setVar("fil_30_arquivo", $uploader->getSavedFileName());
				$file->setVar("fil_30_mime", $uploader->getMediaType());
				$file->setVar("fil_10_tamanho", $uploader->getMediaSize());
				$file->setVar("fil_12_exibir", $_POST['fil_12_exibir']);
				$file->setVar("fil_22_data", time());
				if(!$file->store()) {
					echo "<fieldset><legend>"._ERRORS."</legend>";
					xoops_error(MPU_ADM_PAGEERRORDB);
					echo "</fieldset>";
				}else{
					echo "<fieldset><legend>".MPU_ADM_SENFIL_SUCESS."</legend>";
					echo '<table style="width:100%;"><thead><tr>
					<td>&nbsp;</td>
					<td style="border: 1px double black; text-align: center">'.MPU_ADM_FIL_30_NOME.'</td>
					<td style="border: 1px double black; text-align: center">'.MPU_ADM_FIL_10_TAMANHO.'</td>
					<td style="border: 1px double black; text-align: center">'.MPU_ADM_FIL_22_DATA.'</td>
					<td style="border: 1px double black; text-align: center">'.MPU_ADM_ACAO.'</td>
					</tr></thead><tbody>
					';
					$file_url = MPU_FILES_URL."/".$uploader->getSavedFileName();
					echo '<tr><td width="30%" style="text-align: center">';
					echo '<a href="javascript:void(0)" style="border:2px solid white" onclick="addItem(\''.$file_url.'\', \''.$file->getVar('fil_30_nome').'\')"/>'.$file->getVar('fil_10_id').'</a>';
					echo '</td><td style="border: 2px double #F0F0EE; text-align: center">'.$file->getVar('fil_30_nome').'</td><td style="border: 2px double #F0F0EE; text-align: center">'.number_format($file->getVar("fil_10_tamanho")/1024, 2, ".", "").'Kb</td><td style="border: 2px double #F0F0EE; text-align: center">'.date("d/m/Y", $file->getVar('fil_22_data')).'</td>';
					echo '<td style="border: 2px double #F0F0EE; text-align: center"><a href="javascript:void(0)" onclick="addItem(\''.$file_url.'\', \''.$file->getVar('fil_30_nome').'\')">'._SELECT.'</a></td></tr>';
					echo "</tbody></table></fieldset>";
				}
			} else {
				echo "<fieldset><legend>"._ERRORS."</legend>";
				xoops_error($uploader->getErrors(), MPU_ADM_SENDERROR);
				echo "</fieldset>";
			}
		} else {
			echo "<fieldset><legend>"._ERRORS."</legend>";
			xoops_error($uploader->getErrors());
			echo "</fieldset>";
		}
	}else{
		echo "<fieldset><legend>"._ERRORS."</legend>";
		xoops_error(MPU_ADM_ERR_SELECT_FILE);
		echo "</fieldset>";
	}
}
echo "<h4>".MPU_ADM_NFILE."</h4>";
$fil_classe =& new mpu_fil_files();
$fil_form = new XoopsThemeForm("", "mpu_fil_form", $_SERVER['PHP_SELF'], "post");
$fil_form->setExtra('enctype="multipart/form-data"');
$fil_form->addElement(new XoopsFormText(MPU_ADM_FIL_30_NOME, "fil_30_nome", 50, 50, $fil_classe->getVar("fil_30_nome")), true);
$fil_arquivo = new XoopsFormFile('', 'fil_30_arquivo', $xoopsModuleConfig['mpu_max_filesize']*1024);
$arquivo_tray = new XoopsFormElementTray(MPU_ADM_FIL_30_ARQUIVO, '&nbsp;');
$arquivo_tray->addElement($fil_arquivo);
$fil_form->addElement($arquivo_tray);
$fil_form->addElement(new XoopsFormRadioYN(MPU_ADM_FIL_12_EXIBIR, 'fil_12_exibir',$fil_classe->getVar("fil_12_exibir")));
$fil_form->addElement(new XoopsFormHidden('fil_10_id', $fil_classe->getVar('fil_10_id')));
$fil_form->addElement(new XoopsFormHidden('op', "addfile"));
$fil_botoes_tray = new XoopsFormElementTray("", "&nbsp;&nbsp;");
$fil_botao_cancel = new XoopsFormButton("", "cancelar", _CANCEL);
$fil_botoes_tray->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
$fil_botao_cancel->setExtra("onclick=\"document.location= '".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/files.php'\"");
$fil_botoes_tray->addElement($fil_botao_cancel);
$fil_form->addElement($fil_botoes_tray);
$fil_form->display();
?>
</div>
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
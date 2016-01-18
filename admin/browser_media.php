<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo responsável pela integração do Cadastro de Mídia com
### o TinyMCE
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
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
$tipos = array(1=>MPU_ADM_MED_10_TIPO_1, 2=>MPU_ADM_MED_10_TIPO_2, 3=>MPU_ADM_MED_10_TIPO_3,4=>MPU_ADM_MED_10_TIPO_4,5=>MPU_ADM_MED_10_TIPO_5);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$lang_browser_procurar}</title>
<script language="javascript" type="text/javascript" src="<?=$mpb_wysiwyg_url?>/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="<?=$mpb_wysiwyg_url?>/utils/mctabs.js"></script>
<script language="javascript" type="text/javascript">
<!--
function addItem(itemurl, nome, larg, alt) {
	var campo = tinyMCE.getWindowArg('campo');
	var win = tinyMCE.getWindowArg('win');
	win.document.getElementById(campo).value=itemurl;
	if(win.document.getElementById('name')) win.document.getElementById('name').value=nome;
	if(win.document.getElementById('width')) win.document.getElementById('width').value=larg;
	if(win.document.getElementById('height')) win.document.getElementById('height').value=alt;
	if(campo == "src"){
		if(win.selectByValue) win.selectByValue(win.document.forms[0],'linklist',itemurl);
		if(win.switchType) win.switchType(itemurl);
		if(win.generatePreview()) win.generatePreview();
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
function LargAlt(){
	var tipo = document.forms[1].med_10_tipo;
	if(tipo.options[tipo.selectedIndex].value == 1){
		document.getElementById('largalt').style.display="none";
	}else{
		document.getElementById('largalt').style.display="";
	}
}
//-->
</script>
	<base target="_self" />
</head>
<body onload="tinyMCEPopup.executeOnLoad('init();');">
		<div class="tabs">
			<ul>
				<li id="gerenciador_tab" <?php echo ($op == "listmed" || $op == "list") ? ' class="current"' : '';?>><span><a href="javascript:mcTabs.displayTab('gerenciador_tab','gerenciador_panel');" onmousedown="return false;">{$lang_browser_ger_medias}</a></span></li>
				<li id="nova_media_tab" <?php echo ($op == "addmedia") ? ' class="current"' : '';?>><span><a href="javascript:mcTabs.displayTab('nova_media_tab','nova_media_panel');" onmousedown="return false;">{$lang_browser_nova_media}</a></span></li>
			</ul>
		</div>
<div class="panel_wrapper">
			<div id="gerenciador_panel" class="panel <?php echo ($op == "listmed" || $op == "list") ? 'current' : '';?>" style="overflow: auto;">
				<h3>{$lang_browser_media_title}</h3>
<?PHP
if ($op == 'listmed') {
	$med_10_tipo = intval($_GET['med_10_tipo']);
	$med_classe = new mpu_med_media();
	$criterio = new CriteriaCompo(new Criteria("med_10_tipo", $med_10_tipo));
	if(!empty($_GET['med_30_nome'])){
		$criterio->add(new Criteria("med_30_nome", "%".$_GET['med_30_nome']."%", "LIKE"));
	}
	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
	$criterio->setStart($start);
	$criterio->setLimit(20);
	$medias = $med_classe->PegaTudo($criterio);
	$medias_total = $med_classe->contar($criterio);
	if (!$medias) {
		header("Location: ".$_SERVER['PHP_SELF']."?med_10_tipo=".$med_10_tipo."&erro=".urlencode(MPU_ADM_ERRO_MED404).((!empty($_GET['med_30_nome'])) ? "&med_30_nome=".urlencode($_GET['med_30_nome']) : ""));
	}else{
		$tipos_select = "";
		for ($i = 1; $i <= 5; $i++) {
			$tipos_select .= "<option value='".$i."' ".((!empty($_GET['med_10_tipo']) && $_GET['med_10_tipo'] == $i) ? "selected='selected'" : "").">".$tipos[$i]."</option>\n";
		}
		echo '<h4><a href="'.$_SERVER['PHP_SELF'].'">'. MPU_ADM_BROWSER_GER_MED .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$tipos[$med_10_tipo].'</h4>';
		echo "<fieldset><legend>".MPU_ADM_FILTROS."</legend>";
		echo "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
		echo MPU_ADM_MED_30_NOME." <input type='hidden' name='op' value='listmed'> <input type='text' name='med_30_nome' value='".((!empty($_GET['med_30_nome'])) ? $_GET['med_30_nome'] : "")."'> ".MPU_ADM_MED_10_TIPO." <select name='med_10_tipo'>".$tipos_select."</select> <input type='image' src='".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/images/envia.gif' align='absmiddle' style='border:0'>";
		echo "</form></fieldset><br />";
		echo '<table style="width:100%;"><thead><tr>
	<td>&nbsp;</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_MED_30_NOME.'</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_MED_10_LARGURA.' X '.MPU_ADM_MED_10_ALTURA.'</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_MED_10_TAMANHO.'</td>
	<td style="border: 1px double black; text-align: center">'.MPU_ADM_ACAO.'</td>
	</tr></thead><tbody>
	';
		foreach ($medias as $med) {
			$media_url = MPU_MEDIA_URL."/".$med->getVar('med_30_arquivo');
			echo '<tr><td width="30%" style="text-align: center">';
			echo '<a href="javascript:void(0)" style="border:2px solid white" onclick="addItem(\''.$media_url.'\', \''.$med->getVar('med_30_nome').'\', \''.$med->getVar('med_10_largura').'\', \''.$med->getVar('med_10_altura').'\')"/>'.$med->getVar('med_10_id').'</a>';
			echo '</td><td style="border: 2px double #F0F0EE; text-align: center">'.$med->getVar('med_30_nome').'</td><td style="border: 2px double #F0F0EE; text-align: center">'.$med->getVar('med_10_largura').'px X '.$med->getVar('med_10_altura').'px</td><td style="border: 2px double #F0F0EE; text-align: center">'.number_format($med->getVar("med_10_tamanho")/1024, 2, ".", "").'Kb</td>';
			echo '<td style="border: 2px double #F0F0EE; text-align: center"><a href="javascript:void(0)" onclick="addItem(\''.$media_url.'\', \''.$med->getVar('med_30_nome').'\', \''.$med->getVar('med_10_largura').'\', \''.$med->getVar('med_10_altura').'\')">'._SELECT.'</a></td></tr>';
		}
		echo "</tbody></table>";
		if ($medias_total > 0) {
			if ($medias_total > 20) {
				include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
				$nav = new XoopsPageNav($medias_total, 20, $start, 'start', 'op=listmed&amp;med_10_tipo='.$med_10_tipo.((!empty($_GET['med_30_nome'])) ? "&amp;med_30_nome=".$_GET['med_30_nome'] : ""));
				echo '<div style="text-align:right">'.$nav->renderNav().'</div>';
			}
		}
	}
}else{
	$med_classe = new mpu_med_media();
	$tipos_select = "";
	echo '<ul>';
	for ($i = 1; $i <= 5; $i++) {
		$tipos_select .= "<option value='".$i."' ".((!empty($_GET['med_10_tipo']) && $_GET['med_10_tipo'] == $i) ? "selected='selected'" : "").">".$tipos[$i]."</option>\n";
		$medias = $med_classe->contar(new Criteria('med_10_tipo', $i));
		echo '<li>'.$tipos[$i].' (<b>'.$medias.'</b> '.MPU_ADM_BROWSER_GER_MED.') '.(($medias > 0) ? '[<a href="'.$_SERVER['PHP_SELF'].'?op=listmed&amp;med_10_tipo='.$i.'">'._LIST.'</a>]</li>' : '');
	}
	echo '</ul>';
	echo "<fieldset><legend>".MPU_ADM_FILTROS."</legend>";
	echo "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
	echo MPU_ADM_MED_30_NOME." <input type='hidden' name='op' value='listmed'> <input type='text' name='med_30_nome' value='".((!empty($_GET['med_30_nome'])) ? $_GET['med_30_nome'] : "")."'> ".MPU_ADM_MED_10_TIPO." <select name='med_10_tipo'>".$tipos_select."</select> <input type='image' src='".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/images/envia.gif' align='absmiddle' style='border:0'>";
	echo "</form></fieldset>";
	if (!empty($_GET['erro'])) {
		echo "<br /><div style='border: 2px solid red; text-align:center; font-weight:bold'>".$_GET['erro']."</div>";
	}
}
?>
</div>

			<div id="nova_media_panel" class="panel <?php echo ($op == "addmedia") ? 'current' : '';?>" style="overflow: visible;">
<?PHP
if ($op == 'addmedia') {
	$media = new mpu_med_media();
	$file_nome = $_FILES[$_POST['xoops_upload_file'][0]];
	$file_nome = (get_magic_quotes_gpc()) ? stripslashes($file_nome['name']) : $file_nome['name'];
	if(xoops_trim($file_nome!='')) {
		include_once(XOOPS_ROOT_PATH."/class/uploader.php");
		switch ($_POST['med_10_tipo']){
			case 1:
				$permittedtypes=array('application/x-shockwave-flash');
				break;
			case 2:
				$permittedtypes=array('video/quicktime');
				break;
			case 3:
				$permittedtypes=array('application/x-director');
				break;
			case 4:
				$permittedtypes=array('application/octet-stream', 'video/x-ms-asf', 'video/x-msvideo', 'video/x-ms-wmv');
				break;
			case 5:
			default:
				$permittedtypes=array('audio/x-pn-realaudio');
				break;
		}
		$uploader = new XoopsMediaUploader( MPU_MEDIA_PATH, $permittedtypes, $xoopsModuleConfig['mpu_mmax_filesize']*1024);
		$uploader->extensionToMime = array_merge($uploader->extensionToMime, array("wmv"=>"video/x-ms-wmv","asf"=>"video/x-ms-asf", "rm"=>"audio/x-pn-realaudio"));
		unset($uploader->imageExtensions[4]);
		$uploader->setPrefix("media_");
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if ($uploader->upload()) {
				$media->setVar("med_30_nome", $_POST['med_30_nome']);
				$media->setVar("med_30_arquivo", $uploader->getSavedFileName());
				$largalt = @getimagesize(MPU_MEDIA_PATH."/".$uploader->getSavedFileName());
				if($largalt){
					$media->setVar("med_10_largura", $largalt[0]);
					$media->setVar("med_10_altura", $largalt[1]);
				}else{
					$media->setVar("med_10_altura", $_POST['med_10_altura']);
					$media->setVar("med_10_largura", $_POST['med_10_largura']);
				}
				$media->setVar("med_10_tamanho", $uploader->getMediaSize());
				$media->setVar("med_12_exibir", $_POST['med_12_exibir']);
				$media->setVar("med_22_data", time());
				$media->setVar("med_10_tipo", $_POST['med_10_tipo']);
				if(!$media->store()) {
					echo "<fieldset><legend>"._ERRORS."</legend>";
					xoops_error(MPU_ADM_PAGEERRORDB);
					echo "</fieldset>";
				}else{
					echo "<fieldset><legend>".MPU_ADM_SENMED_SUCESS."</legend>";
					echo '<table style="width:100%;"><thead><tr>
					<td>&nbsp;</td>
					<td style="border: 1px double black; text-align: center">'.MPU_ADM_MED_30_NOME.'</td>
					<td style="border: 1px double black; text-align: center">'.MPU_ADM_MED_10_LARGURA.' X '.MPU_ADM_MED_10_ALTURA.'</td>
					<td style="border: 1px double black; text-align: center">'.MPU_ADM_MED_10_TAMANHO.'</td>
					<td style="border: 1px double black; text-align: center">'.MPU_ADM_ACAO.'</td>
					</tr></thead><tbody>
					';
					$media_url = MPU_MEDIA_URL."/".$uploader->getSavedFileName();
					echo '<tr><td width="30%" style="text-align: center">';
					echo '<a href="javascript:void(0)" style="border:2px solid white" onclick="addItem(\''.$media_url.'\', \''.$media->getVar('med_30_nome').'\', \''.$media->getVar('med_10_largura').'\', \''.$media->getVar('med_10_altura').'\')"/>'.$media->getVar('med_10_id').'</a>';
					echo '</td><td style="border: 2px double #F0F0EE; text-align: center">'.$media->getVar('med_30_nome').'</td><td style="border: 2px double #F0F0EE; text-align: center">'.$media->getVar('med_10_largura').'px X '.$media->getVar('med_10_altura').'px</td><td style="border: 2px double #F0F0EE; text-align: center">'.number_format($media->getVar("med_10_tamanho")/1024, 2, ".", "").'Kb</td>';
					echo '<td style="border: 2px double #F0F0EE; text-align: center"><a href="javascript:void(0)" onclick="addItem(\''.$media_url.'\', \''.$media->getVar('med_30_nome').'\', \''.$media->getVar('med_10_largura').'\', \''.$media->getVar('med_10_altura').'\')">'._SELECT.'</a></td></tr>';
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
		xoops_error(MPU_ADM_ERR_SELECT_MEDIA);
		echo "</fieldset>";
	}
}
echo "<h4>".MPU_ADM_NMEDIA."</h4>";
$med_classe =& new mpu_med_media();
$med_form = new XoopsThemeForm("", "mpu_med_form", $_SERVER['PHP_SELF'], "post");
$med_form->setExtra('enctype="multipart/form-data"');
$med_form->addElement(new XoopsFormText(MPU_ADM_MED_30_NOME, "med_30_nome", 50, 50), true);
$tipo_select = new XoopsFormSelect("", "med_10_tipo");
$tipo_select->setExtra("onchange='LargAlt();'");
$tipo_select->addOptionArray($tipos);
$tipo_tray = new XoopsFormElementTray(MPU_ADM_MED_10_TIPO);
$tipo_tray->addElement($tipo_select);
$tipo_tray->addElement(new XoopsFormLabel("", "<br /><div id='largalt' style='display:none'>"));
$tipo_tray->addElement(new XoopsFormText(MPU_ADM_MED_10_LARGURA, "med_10_largura", 4, 4), true);
$tipo_tray->addElement(new XoopsFormText(MPU_ADM_MED_10_ALTURA, "med_10_altura", 4, 4), true);
$tipo_tray->addElement(new XoopsFormLabel("", "</div>"));
$med_form->addElement($tipo_tray);
$med_arquivo = new XoopsFormFile('', 'med_30_arquivo', 50000000);
$arquivo_tray = new XoopsFormElementTray(MPU_ADM_MED_30_ARQUIVO, '&nbsp;');
$arquivo_tray->addElement($med_arquivo);
$med_form->addElement($arquivo_tray);
$med_form->addElement(new XoopsFormRadioYN(MPU_ADM_MED_12_EXIBIR, 'med_12_exibir', 1));
$med_form->addElement(new XoopsFormHidden('op', "addmedia"));
$med_botoes_tray = new XoopsFormElementTray("", "&nbsp;&nbsp;");
$med_botao_cancel = new XoopsFormButton("", "cancelar", _CANCEL);
$med_botoes_tray->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
$med_botao_cancel->setExtra("onclick=\"document.location= '".$_SERVER['PHP_SELF']."'\"");
$med_botoes_tray->addElement($med_botao_cancel);
$med_form->addElement($med_botoes_tray);
$med_form->display();
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
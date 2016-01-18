<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Formulário de Envio de Mídias
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
if (!defined('XOOPS_ROOT_PATH')) {
	die("Erro! XOOPS_ROOT_PATH não está definido");
}
echo <<<JSCRIPT
<script type="text/javascript">
<!--
function LargAlt(){
var tipo = document.getElementById("med_10_tipo");
var id = document.getElementById("med_10_id");

if(tipo.options[tipo.selectedIndex].value == 1 && id.value == ""){
document.getElementById('largalt').style.display="none";
}else{
document.getElementById('largalt').style.display="";
}
}
-->
</script>

JSCRIPT;
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$med_form = new XoopsThemeForm($form['titulo'], "mpu_med_form", $_SERVER['PHP_SELF'], "post");
$med_form->setExtra('enctype="multipart/form-data"');
if($med_classe->getVar("med_10_id") != ""){
	$arquivo = MPU_MEDIA_URL."/".$med_classe->getVar("med_30_arquivo");
	$altura = ($med_classe->getVar("med_10_altura") > 0) ?  $med_classe->getVar("med_10_altura") : 200;
	$largura = ($med_classe->getVar("med_10_largura") > 0) ?  $med_classe->getVar("med_10_largura") : 200;
	switch ($med_classe->getVar("med_10_tipo")) {
		case 1:
			$cod_html = '
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="'.$largura.'" height="'.$altura.'">
<param name="src" value="'.$arquivo.'" />
<param name="width" value="'.$largura.'" />
<param name="height" value="'.$altura.'" />
<embed type="application/x-shockwave-flash" src="'.$arquivo.'" width="'.$largura.'" height="'.$altura.'"></embed>
</object>';
			break;
		case 2:
		$cod_html = '
<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0" width="'.$largura.'" height="'.$altura.'">
<param name="src" value="'.$arquivo.'" />
<param name="width" value="'.$largura.'" />
<param name="height" value="'.$altura.'" />
<embed type="video/quicktime" src="'.$arquivo.'" width="'.$largura.'" height="'.$altura.'"></embed>
</object>
		';
			break;
		case 3:
		$cod_html = '
<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,1,0" width="'.$largura.'" height="'.$altura.'">
<param name="sound" value="true" />
<param name="progress" value="true" />
<param name="autostart" value="true" />
<param name="swstretchstyle" value="none" />
<param name="swstretchhalign" value="none" />
<param name="swstretchvalign" value="none" />
<param name="src" value="'.$arquivo.'" />
<param name="width" value="'.$largura.'" />
<param name="height" value="'.$altura.'" />
<embed type="application/x-director" sound="true" progress="true" autostart="true" swliveconnect="false" swstretchstyle="none" swstretchhalign="none" swstretchvalign="none" src="'.$arquivo.'" width="'.$largura.'" height="'.$altura.'"></embed>
</object>
		';
		break;
		case 4:
		$cod_html = '
<object classid="clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701" width="'.$largura.'" height="'.$altura.'">
<param name="src" value="'.$arquivo.'" />
<param name="url" value="'.$arquivo.'" />
<param name="width" value="'.$largura.'" />
<param name="height" value="'.$altura.'" />
<embed type="application/x-mplayer2" src="'.$arquivo.'" width="'.$largura.'" height="'.$altura.'"></embed>
</object>
		';
			break;
		case 5:
		default:
		$cod_html = '
<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="'.$largura.'" height="'.$altura.'">
<param name="autostart" value="true" />
<param name="src" value="'.$arquivo.'" />
<param name="width" value="'.$largura.'" />
<param name="height" value="'.$altura.'" />
<embed type="audio/x-pn-realaudio-plugin" autostart="true" src="'.$arquivo.'" width="'.$largura.'" height="'.$altura.'"></embed>
</object>
		';
			break;
	}
	$arq_html = new XoopsFormElementTray(MPU_ADM_MPB_HTML, "<br />");
	$arq_html->addElement(new XoopsFormLabel("", $cod_html));
	$arq_html->addElement(new XoopsFormLabel("", "<pre>".htmlspecialchars($cod_html)."</pre>"));
	$med_form->addElement($arq_html);
}
$med_form->addElement(new XoopsFormText(MPU_ADM_MED_30_NOME, "med_30_nome", 50, 50, $med_classe->getVar("med_30_nome")), true);
$tipo_select = new XoopsFormSelect("", "med_10_tipo", $med_classe->getVar("med_10_tipo"));
$tipo_select->setExtra("onchange='LargAlt();'");
$tipo_select->addOptionArray(array(1 => MPU_ADM_MED_10_TIPO_1, 2=>MPU_ADM_MED_10_TIPO_2, 3=>MPU_ADM_MED_10_TIPO_3, 4=>MPU_ADM_MED_10_TIPO_4, 5=>MPU_ADM_MED_10_TIPO_5));
$tipo_tray = new XoopsFormElementTray(MPU_ADM_MED_10_TIPO);
$tipo_tray->addElement($tipo_select);
$tipo_tray->addElement(new XoopsFormLabel("", "<br /><div id='largalt' ".(($med_classe->getVar("med_10_id") <= 0) ? "style='display:none'" : "").">"));
$tipo_tray->addElement(new XoopsFormText(MPU_ADM_MED_10_LARGURA, "med_10_largura", 4, 4, $med_classe->getVar("med_10_largura")), true);
$tipo_tray->addElement(new XoopsFormText(MPU_ADM_MED_10_ALTURA, "med_10_altura", 4, 4, $med_classe->getVar("med_10_altura")), true);
$tipo_tray->addElement(new XoopsFormLabel("", "</div>"));
$med_form->addElement($tipo_tray);
$med_arquivo = new XoopsFormFile('', 'med_30_arquivo', $xoopsModuleConfig['mpu_mmax_filesize']*1024);
$arquivo_tray = new XoopsFormElementTray(MPU_ADM_MED_30_ARQUIVO, '&nbsp;');
$arquivo_tray->addElement($med_arquivo);
$med_form->addElement($arquivo_tray);
$med_form->addElement(new XoopsFormRadioYN(MPU_ADM_MED_12_EXIBIR, 'med_12_exibir',$med_classe->getVar("med_12_exibir")));
$med_form->addElement(new XoopsFormHidden('med_10_id', $med_classe->getVar('med_10_id')));
$med_form->addElement(new XoopsFormHidden('op', $form['op']));
$med_botoes_tray = new XoopsFormElementTray("", "&nbsp;&nbsp;");
$med_botao_cancel = new XoopsFormButton("", "cancelar", _CANCEL);
$med_botoes_tray->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
$med_botao_cancel->setExtra("onclick=\"document.location= '".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/media.php'\"");
$med_botoes_tray->addElement($med_botao_cancel);
$med_form->addElement($med_botoes_tray);
?>
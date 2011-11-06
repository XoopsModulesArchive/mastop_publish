<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Bloco do Menu de Páginas Relacionadas
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
if (!defined('MPU_MOD_DIR')) {
	if ( file_exists(XOOPS_ROOT_PATH."/modules/".MPU_BLO_MODDIR."/language/".$xoopsConfig['language']."/modinfo.php") ) {
		include_once(XOOPS_ROOT_PATH."/modules/".MPU_BLO_MODDIR."/language/".$xoopsConfig['language']."/modinfo.php");
	} else {
		include_once(XOOPS_ROOT_PATH."/modules/".MPU_BLO_MODDIR."/language/portuguesebr/modinfo.php");
	}
}
function mpu_menurelated_exibe($options){
	include_once XOOPS_ROOT_PATH."/modules/".MPU_BLO_MODDIR."/class/mpu_mpb_mpublish.class.php";
	$tac = (isset($_GET['tac'])) ? $_GET['tac'] : 0;
	$tac = (is_int($tac)) ? $tac : str_replace("_"," ", $tac);
	$block = array();
	$block['menurelateds'] = 0;
	if(!$tac){
		return false;
	}else{
		$mpu_classe = new mpu_mpb_mpublish($tac);
		if ($mpu_classe->getVar("mpb_10_id") != "" && $mpu_classe->getVar("mpb_10_idpai") != 0) {
			$rel_crit = new CriteriaCompo(new Criteria("mpb_10_idpai", $mpu_classe->getVar("mpb_10_idpai")));
			$rel_crit->add(new Criteria("mpb_10_id", $mpu_classe->getVar("mpb_10_id"), "<>"));
			$rel_crit->add(new Criteria("mpb_12_semlink", 0));
			$rel_crit2 = new CriteriaCompo(new Criteria("mpb_11_visivel", 2));
			$rel_crit2->add(new Criteria("mpb_11_visivel", 3), "OR");
			$rel_crit->add($rel_crit2);
			if ($mpu_classe->contar($rel_crit) <= 0) {
				return false;
			}
			$block['menuID'] = $options[0];
			$block['menuWidth'] = $options[1];
			$block['menuWidthSub'] = $options[2];
			$block['menuArrow'] = $options[3];
			$block['menuBG'] = $options[4];
			$block['menuBGO'] = $options[5];
			$block['menuTColor'] = $options[6];
			$block['menuTOColor'] = $options[7];
			$block['menuVisited'] = $options[8];
			$block['menuN'] = (!empty($options[9])) ? "bold" : "normal";
			$block['menuI'] = (!empty($options[10])) ? "italic" : "normal";
			$block['menuU'] = (!empty($options[11])) ? "underline" : "none";
			$block['menuON'] = (!empty($options[12])) ? "bold" : "normal";
			$block['menuOI'] = (!empty($options[13])) ? "italic" : "normal";
			$block['menuOU'] = (!empty($options[14])) ? "underline" : "none";
			$block['menuBorderW'] = $options[15];
			$block['menuBorderS'] = $options[16];
			$block['menuBorderC'] = $options[17];
			$block['menuBorderOW'] = $options[18];
			$block['menuBorderOS'] = $options[19];
			$block['menuBorderOC'] = $options[20];
			$block['menuPadding'] = $options[21];
			$block['menuRelated'] = $mpu_classe->geraMenuRelated(null);
			$block['title'] = "Fernando";
			$block['menurelateds'] = 1;
			return $block;
		}else{
			return false;
		}
	}
}
function mpu_menurelated_edita($options){
	$picker_url = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/color_picker';
	$form = '
	<style type="text/css">
<!--
#plugin { BACKGROUND: #0d0d0d; COLOR: #AAA; CURSOR: move; DISPLAY: none; FONT-FAMILY: arial; FONT-SIZE: 11px; PADDING: 7px 10px 11px 10px; _PADDING-RIGHT: 0; Z-INDEX: 1;  POSITION: absolute; WIDTH: 199px; _width: 210px; _padding-right: 0px; }
#plugin br { CLEAR: both; MARGIN: 0; PADDING: 0;  }
#plugin select { BORDER: 1px solid #333; BACKGROUND: #FFF; POSITION: relative; TOP: 4px; }

#plugHEX { FLOAT: left; }
#plugCLOSE { CURSOR: pointer; FLOAT: right; MARGIN: 0 8px 3px; _MARGIN-RIGHT: 10px; COLOR: #FFF; -moz-user-select: none; -khtml-user-select: none; user-select: none; }
#plugHEX:hover,#plugCLOSE:hover { COLOR: #FFD000;  }

#SV { background: #FF0000 url("'.$picker_url.'/SatVal.png"); _BACKGROUND: #FF0000; POSITION: relative; CURSOR: crosshair; FLOAT: left; HEIGHT: 166px; WIDTH: 167px; _WIDTH: 165px; MARGIN-RIGHT: 10px; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src="'.$picker_url.'/SatVal.png", sizingMethod="scale"); -moz-user-select: none; -khtml-user-select: none; user-select: none; }
#SVslide { BACKGROUND: url("'.$picker_url.'/slide.gif"); HEIGHT: 9px; WIDTH: 9px; POSITION: absolute; _font-size: 1px; line-height: 1px; }

#H { BORDER: 1px solid #000; CURSOR: crosshair; FLOAT: left; HEIGHT: 154px; POSITION: relative; WIDTH: 19px; PADDING: 0; TOP: 4px; -moz-user-select: none; -khtml-user-select: none; user-select: none; }
#Hslide { BACKGROUND: url("'.$picker_url.'/slideHue.gif"); HEIGHT: 5px; WIDTH: 33px; POSITION: absolute; _font-size: 1px; line-height: 1px; }
#Hmodel { POSITION: relative; TOP: -5px; }
#Hmodel div { HEIGHT: 1px; WIDTH: 19px; font-size: 1px; line-height: 1px; MARGIN: 0; PADDING: 0; }
-->
</style>
 <script src="'.$picker_url.'/plugin.js" type="text/JavaScript"></script>
 <script type="text/javascript">
var atual_color = "campo_img";
var atual_campo = "campo";
function pegaPicker(campo, e){
atual_color = campo.name+"_img";
atual_campo = campo.name;
$S("plugin").left= (XY(e)-10)+"px";
$S("plugin").top= (XY(e,1)+10)+"px";
toggle("plugin");
updateH(campo.value);
$("plugHEX").innerHTML=campo.value
loadSV();
}

function mkColor(v) {
$S(atual_color).background="#"+v;
$(atual_campo).value=v;
}
function troca(campo, nome){
if(campo.checked){
$(nome).value = 1;
}else{
$(nome).value = 0;
}
}
</script>
	';
	$form .=
	<<< PICKER
	<div id="plugin" onmousedown="HSVslide('drag','plugin',event)" style="Z-INDEX: 20; display:none">
 <div id="plugHEX" onmousedown="stop=0; setTimeout('stop=1',100); toggle('plugin');">&nbsp</div><div id="plugCLOSE" onmousedown="toggle('plugin')">X</div><br>
 <div id="SV" onmousedown="HSVslide('SVslide','plugin',event)" title="Saturation + Value">
  <div id="SVslide" style="TOP: -4px; LEFT: -4px;"><br /></div>
 </div>
 <div id="H" onmousedown="HSVslide('Hslide','plugin',event)" title="Hue">
  <div id="Hslide" style="TOP: -7px; LEFT: -8px;"><br /></div>
  <div id="Hmodel"></div>
 </div>
</div>
PICKER;


	$form .= MPU_BLO_OPT_ID." <input type='text' name='options[0]' value='".$options[0]."' /><br />";
	$form .= MPU_BLO_OPT_WIDTH." <input type='text' size='4' name='options[1]' value='".$options[1]."' /><br />";
	$form .= MPU_BLO_OPT_WIDTH_SUB." <input type='text' size='4' name='options[2]' value='".$options[2]."' /><br />";
	$form .= MPU_BLO_OPT_URLARROW." <input type='text' size='35' name='options[3]' value='".$options[3]."' onblur=\"document.getElementById('menuImage').src=this.value\" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img id='menuImage' src='".$options[3]."'><br />";
	$form .= MPU_BLO_OPT_BGMENU. ' #<input size="6" type="text" name="options[4]" id="options[4]" value="'.$options[4].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[4]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[4]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[4].'"><br />';
	$form .= MPU_BLO_OPT_BGMENUO. ' #<input size="6" type="text" name="options[5]" id="options[5]" value="'.$options[5].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[5]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[5]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[5].'"><br />';
	$form .= MPU_BLO_OPT_MENUTCOLOR. ' #<input size="6" type="text" name="options[6]" id="options[6]" value="'.$options[6].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[6]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[6]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[6].'"><br />';
	$form .= MPU_BLO_OPT_MENUTOCOLOR. ' #<input size="6" type="text" name="options[7]" id="options[7]" value="'.$options[7].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[7]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[7]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[7].'"><br />';
	$form .= MPU_BLO_OPT_MENUVISITED. ' #<input size="6" type="text" name="options[8]" id="options[8]" value="'.$options[8].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[8]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[8]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[8].'"><br />';
	$form .= MPU_BLO_OPT_FONT. "<input type='hidden' name='options[9]' id='options[9]' value='".((!empty($options[9])) ? "1" : "0")."'><label><input type='checkbox' name='chk[12]' value='1' ".((!empty($options[9])) ? " checked='checked'" : "")." onclick='troca(this, \"options[9]\")'> <b>".MPU_BLO_OPT_BOLD."</b></label>&nbsp;&nbsp;&nbsp;<input type='hidden' name='options[10]' id='options[10]' value='".((!empty($options[10])) ? "1" : "0")."'><label><input type='checkbox' name='chk[13]' value='1' ".((!empty($options[10])) ? " checked='checked'" : "")." onclick='troca(this, \"options[10]\")'> <i>".MPU_BLO_OPT_ITALIC."</i></label>&nbsp;&nbsp;&nbsp;<input type='hidden' name='options[11]' id='options[11]' value='".((!empty($options[11])) ? "1" : "0")."'><label><input type='checkbox' name='options[11]' value='1' ".((!empty($options[11])) ? " checked='checked'" : "")." onclick='troca(this, \"options[11]\")'> <u>".MPU_BLO_OPT_UNDERLINE."</u></label><br />";
	$form .= MPU_BLO_OPT_FONTO. "<input type='hidden' name='options[12]' id='options[12]' value='".((!empty($options[12])) ? "1" : "0")."'><label><input type='checkbox' name='options[12]' value='1' ".((!empty($options[12])) ? " checked='checked'" : "")." onclick='troca(this, \"options[12]\")'> <b>".MPU_BLO_OPT_BOLD."</b></label>&nbsp;&nbsp;&nbsp;<input type='hidden' name='options[13]' id='options[13]' value='".((!empty($options[13])) ? "1" : "0")."'><label><input type='checkbox' name='options[13]' value='1' ".((!empty($options[13])) ? " checked='checked'" : "")." onclick='troca(this, \"options[13]\")'> <i>".MPU_BLO_OPT_ITALIC."</i></label>&nbsp;&nbsp;&nbsp;<input type='hidden' name='options[14]' id='options[14]' value='".((!empty($options[14])) ? "1" : "0")."'><label><input type='checkbox' name='options[14]' value='1' ".((!empty($options[14])) ? " checked='checked'" : "")." onclick='troca(this, \"options[14]\")'> <u>".MPU_BLO_OPT_UNDERLINE."</u></label><br />";
	$form .= MPU_BLO_OPT_BORDER." <input size='4' type='text' name='options[15]' value='".$options[15]."' /> <select name='options[16]'>
	<option value='none'";
	$form .= ($options[16]==('none'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_NONE."</option>
    <option value='solid'";
	$form .= ($options[16]==('solid'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_SOLID."</option>
    <option value='double'";
	$form .= ($options[16]==('double'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_DOUBLE."</option>
    <option value='outset'";
	$form .= ($options[16]==('outset'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_OUTSET."</option>
    <option value='inset'";
	$form .= ($options[16]==('inset'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_INSET."</option>
	<option value='groove'";
	$form .= ($options[16]==('groove'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_GROOVE."</option>
	<option value='ridge'";
	$form .= ($options[16]==('ridge'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_RIDGE."</option>
    <option value='dashed'";
	$form .= ($options[16]==('dashed'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_DASHED."</option>
    <option value='dotted'";
	$form .= ($options[16]==('dotted'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_DOTTED."</option></select>";
	$form .= ' #<input size="6" type="text" name="options[17]" id="options[17]" value="'.$options[17].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[17]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[17]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[17].'"><br />';
	$form .= MPU_BLO_OPT_BORDERO." <input size='4' type='text' name='options[18]' value='".$options[18]."' /> <select name='options[19]'>
	<option value='none'";
	$form .= ($options[19]==('none'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_NONE."</option>
    <option value='solid'";
	$form .= ($options[19]==('solid'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_SOLID."</option>
    <option value='double'";
	$form .= ($options[19]==('double'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_DOUBLE."</option>
    <option value='outset'";
	$form .= ($options[19]==('outset'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_OUTSET."</option>
    <option value='inset'";
	$form .= ($options[19]==('inset'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_INSET."</option>
	<option value='groove'";
	$form .= ($options[19]==('groove'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_GROOVE."</option>
	<option value='ridge'";
	$form .= ($options[19]==('ridge'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_RIDGE."</option>
    <option value='dashed'";
	$form .= ($options[19]==('dashed'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_DASHED."</option>
    <option value='dotted'";
	$form .= ($options[19]==('dotted'))? 'selected="selected"':' ';
	$form .= ">".MPU_BLO_OPT_DOTTED."</option></select>";
	$form .= ' #<input size="6" type="text" name="options[20]" id="options[20]" value="'.$options[20].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[20]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[20]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[20].'"><br />';
	$form .= MPU_BLO_OPT_PADDING." <input type='text' size='4' name='options[21]' value='".$options[21]."' /><br />";
	return $form;
}
  ?>
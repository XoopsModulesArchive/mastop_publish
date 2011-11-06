<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Bloco do Menu em Árvore
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
function mpu_menutree_exibe($options){
	include_once XOOPS_ROOT_PATH."/modules/".MPU_BLO_MODDIR."/class/mpu_mpb_mpublish.class.php";
	$mpu_classe = new mpu_mpb_mpublish();
	$block = array();
	$block['menusrc'] = XOOPS_URL."/modules/".MPU_BLO_MODDIR."/include/treemenu.js";
	$block['moduleimg'] = XOOPS_URL."/modules/".MPU_BLO_MODDIR."/images/";
	$block['menuID'] = $options[0];
	$block['menuHome'] = $options[1];
	$block['textHome'] = $options[2];
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
	$exibe_subpgs = ($options[15] > 0) ? new CriteriaCompo(new Criteria("mpb_10_idpai", $options[15])) : null ;
	$block['menuTREE'] = $mpu_classe->geraMenuCSS($exibe_subpgs, (($options[15] == 0) ? $options[3] : false));
	return $block;
}
function mpu_menutree_edita($options){
	include_once XOOPS_ROOT_PATH."/modules/".MPU_BLO_MODDIR."/class/mpu_mpb_mpublish.class.php";
	define("MPU_ADM_MENUP", _ALL);
	$mpu_classe = new mpu_mpb_mpublish();
	$paginas = $mpu_classe->geraMenuSelect(0, false);
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
loadSV();
updateH(campo.value);
$("plugHEX").innerHTML=campo.value
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
	$form .= MPU_BLO_OPT_HOME."&nbsp;<input type='radio' name='options[1]' value='1'";
	if ( $options[1] == 1 ) {
		$form .= " checked='checked'";
	}
	$form .= " />&nbsp;"._YES."<input type='radio' name='options[1]' value='0'";
	if ( $options[1] == 0 ) {
		$form .= " checked='checked'";
	}
	$form .= " />&nbsp;"._NO."<br />";
	$form .= MPU_BLO_OPT_HOMETEXT." <input type='text' name='options[2]' value='".$options[2]."' /><br />";
	$form .= MPU_BLO_OPT_MOD."&nbsp;<input type='radio' name='options[3]' value='1'";
	if ( $options[3] == 1 ) {
		$form .= " checked='checked'";
	}
	$form .= " />&nbsp;"._YES."<input type='radio' name='options[3]' value='0'";
	if ( $options[3] == 0 ) {
		$form .= " checked='checked'";
	}
	$form .= " />&nbsp;"._NO."<br />";
	$form .= MPU_BLO_OPT_BGMENU. ' #<input size="6" type="text" name="options[4]" id="options[4]" value="'.$options[4].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[4]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[4]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[4].'"><br />';
	$form .= MPU_BLO_OPT_BGMENUO. ' #<input size="6" type="text" name="options[5]" id="options[5]" value="'.$options[5].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[5]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[5]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[5].'"><br />';
	$form .= MPU_BLO_OPT_MENUTCOLOR. ' #<input size="6" type="text" name="options[6]" id="options[6]" value="'.$options[6].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[6]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[6]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[6].'"><br />';
	$form .= MPU_BLO_OPT_MENUTOCOLOR. ' #<input size="6" type="text" name="options[7]" id="options[7]" value="'.$options[7].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[7]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[7]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[7].'"><br />';
	$form .= MPU_BLO_OPT_MENUVISITED. ' #<input size="6" type="text" name="options[8]" id="options[8]" value="'.$options[8].'" onblur=\'$S(this.name+"_img").background="#"+this.value;\'><img id="options[8]_img" align="absmiddle" src="'.$picker_url.'/color.gif" onmouseover="this.style.border=\'2px solid black\'"  onmouseout="this.style.border=\'2px solid #DEE3E7\'" onclick=\'pegaPicker($("options[8]"), event)\' style="border: 2px solid #DEE3E7; background: #'.$options[8].'"><br />';
	$form .= MPU_BLO_OPT_FONT. "<input type='hidden' name='options[9]' id='options[9]' value='".((!empty($options[9])) ? "1" : "0")."'><label><input type='checkbox' name='chk[12]' value='1' ".((!empty($options[9])) ? " checked='checked'" : "")." onclick='troca(this, \"options[9]\")'> <b>".MPU_BLO_OPT_BOLD."</b></label>&nbsp;&nbsp;&nbsp;<input type='hidden' name='options[10]' id='options[10]' value='".((!empty($options[10])) ? "1" : "0")."'><label><input type='checkbox' name='chk[13]' value='1' ".((!empty($options[10])) ? " checked='checked'" : "")." onclick='troca(this, \"options[10]\")'> <i>".MPU_BLO_OPT_ITALIC."</i></label>&nbsp;&nbsp;&nbsp;<input type='hidden' name='options[11]' id='options[11]' value='".((!empty($options[11])) ? "1" : "0")."'><label><input type='checkbox' name='options[11]' value='1' ".((!empty($options[11])) ? " checked='checked'" : "")." onclick='troca(this, \"options[11]\")'> <u>".MPU_BLO_OPT_UNDERLINE."</u></label><br />";
	$form .= MPU_BLO_OPT_FONTO. "<input type='hidden' name='options[12]' id='options[12]' value='".((!empty($options[12])) ? "1" : "0")."'><label><input type='checkbox' name='options[12]' value='1' ".((!empty($options[12])) ? " checked='checked'" : "")." onclick='troca(this, \"options[12]\")'> <b>".MPU_BLO_OPT_BOLD."</b></label>&nbsp;&nbsp;&nbsp;<input type='hidden' name='options[13]' id='options[13]' value='".((!empty($options[13])) ? "1" : "0")."'><label><input type='checkbox' name='options[13]' value='1' ".((!empty($options[13])) ? " checked='checked'" : "")." onclick='troca(this, \"options[13]\")'> <i>".MPU_BLO_OPT_ITALIC."</i></label>&nbsp;&nbsp;&nbsp;<input type='hidden' name='options[14]' id='options[14]' value='".((!empty($options[14])) ? "1" : "0")."'><label><input type='checkbox' name='options[14]' value='1' ".((!empty($options[14])) ? " checked='checked'" : "")." onclick='troca(this, \"options[14]\")'> <u>".MPU_BLO_OPT_UNDERLINE."</u></label><br />";
	$form .= MPU_BLO_OPT_EXIBE. "<select name='options[15]'>";
	if (!empty($paginas) && is_array($paginas)) {
		foreach ($paginas as $k=>$v) {
			$form .= "<option value='$k'";
			if ($k == $options[15]) {
			$form .=" selected='selected'";
			}
			$form .=">".$v."</option>";
		}
	}
	$form .= "</select>";
	return $form;
}
  ?>
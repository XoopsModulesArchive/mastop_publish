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
include_once("../admin/admin_header.php");
$xoopsLogger->activated = false;
$ret = "";
if (!is_object($xoopsUser) || !$xoopsUserIsAdmin) {
	die("Oops!");
}
$criterio = new CriteriaCompo(new Criteria("med_12_exibir", 1));
$criterio->setSort("med_30_nome");
$med_classe = new mpu_med_media();
$medias = $med_classe->PegaTudo($criterio);
$tipos = array(1=>MPU_ADM_MED_10_TIPO_1, 2=>MPU_ADM_MED_10_TIPO_2, 3=>MPU_ADM_MED_10_TIPO_3,4=>MPU_ADM_MED_10_TIPO_4,5=>MPU_ADM_MED_10_TIPO_5);
if ($med_classe->total > 0) {

	foreach ($medias as $med) {
		$ret .= '["'.$med->getVar('med_30_nome').'  ['.$tipos[$med->getVar('med_10_tipo')].']", "'.MPU_MEDIA_URL.'/'.$med->getVar('med_30_arquivo').'"],';
	}
	$ret = substr($ret, 0, -1);
}
?>
var tinyMCEMediaList = new Array(
	<?=$ret;?>
);
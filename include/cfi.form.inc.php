<?PHP
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Formulário para envio de Páginas
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
if (!defined('XOOPS_ROOT_PATH')) {
	die("Erro! XOOPS_ROOT_PATH não está definido");
}
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$cfi_form = new XoopsThemeForm($form['titulo'], "mpu_cfi_form", $_SERVER['PHP_SELF'], "post");
$cfi_form->setExtra('enctype="multipart/form-data"');
$cfi_form->addElement(new XoopsFormText(MPU_ADM_CFI_30_NOME, "cfi_30_nome", 50, 50, $cfi_classe->getVar("cfi_30_nome")), true);
$cfi_arquivo = new XoopsFormFile('', 'cfi_30_arquivo', $xoopsModuleConfig['mpu_max_filesize']*1024);
$arquivo_tray = new XoopsFormElementTray(MPU_ADM_CFI_30_ARQUIVO, '&nbsp;');
$arquivo_tray->addElement($cfi_arquivo);
$cfi_form->addElement($arquivo_tray);
$cfi_form->addElement(new XoopsFormRadioYN(MPU_ADM_CFI_12_EXIBIR, 'cfi_12_exibir',$cfi_classe->getVar("cfi_12_exibir")));
$cfi_form->addElement(new XoopsFormHidden('cfi_10_id', $cfi_classe->getVar('cfi_10_id')));
$cfi_form->addElement(new XoopsFormHidden('op', $form['op']));
$cfi_botoes_tray = new XoopsFormElementTray("", "&nbsp;&nbsp;");
$cfi_botao_cancel = new XoopsFormButton("", "cancelar", _CANCEL);
$cfi_botoes_tray->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
$cfi_botao_cancel->setExtra("onclick=\"document.location= '".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php'\"");
$cfi_botoes_tray->addElement($cfi_botao_cancel);
$cfi_form->addElement($cfi_botoes_tray);
?>
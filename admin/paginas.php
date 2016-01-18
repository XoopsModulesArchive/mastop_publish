<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo para manipulação de Páginas
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once 'admin_header.php';
$op = (isset($_GET['op'])) ? $_GET['op'] : 'contentfiles';
if (isset($_GET)) {
	foreach ($_GET as $k => $v) {
		$$k = $v;
	}
}

if (isset($_POST)) {
	foreach ($_POST as $k => $v) {
		$$k = $v;
	}
}

switch ($op) {
	case "contentfiles_editar":
		mpu_adm_menu();
		$cfi_10_id = (!empty($cfi_10_id)) ? $cfi_10_id : 0;
		$cfi_classe =& new mpu_cfi_contentfiles($cfi_10_id);
		if (empty($cfi_10_id) || $cfi_classe->getVar('cfi_10_id') == '') {
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php", 3, MPU_ADM_ERRO_FIL404);
		}
		$form['titulo'] = MPU_ADM_EFILE;
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/cfi.form.inc.php";
		$cfi_form->display();
		break;
	case "contentfiles_deletar":
		mpu_adm_menu();
		$cfi_10_id = (!empty($cfi_10_id)) ? $cfi_10_id : 0;
		$cfi_classe =& new mpu_cfi_contentfiles($cfi_10_id);
		if (empty($cfi_10_id) || $cfi_classe->getVar('cfi_10_id') == '') {
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php", 3, MPU_ADM_ERRO_FIL404);
		}
		xoops_confirm(array('op' => 'contentfiles_deletar_ok', 'cfi_10_id' => $cfi_10_id), 'paginas.php', sprintf(MPU_ADM_CONFIRMA_DELPG, $cfi_10_id, $cfi_classe->getVar("cfi_30_nome")));
		break;
	case "contentfiles_deletar_ok":
		$cfi_10_id = (!empty($cfi_10_id)) ? $cfi_10_id : 0;
		$cfi_classe =& new mpu_cfi_contentfiles($cfi_10_id);
		if (empty($cfi_10_id) || $cfi_classe->getVar('cfi_10_id') == '') {
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php", 3, MPU_ADM_ERRO_FIL404);
		}
		$cfi_classe->delete();
		$cfi_classe->deletaArquivo();
		redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php", 3,MPU_ADM_DELFIL_SUCESS);
		break;
	case 'contentfiles_adicionar':
		mpu_adm_menu();
		$cfi_classe =& new mpu_cfi_contentfiles();
		$form['titulo'] = MPU_ADM_NPG;
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/cfi.form.inc.php";
		$cfi_form->display();
		break;
	case 'salvar':
		if (empty($cfi_10_id)) {
			$contentfiles = new mpu_cfi_contentfiles();
		}else{
			$contentfiles = new mpu_cfi_contentfiles($cfi_10_id);
		}
		$erro = '';
		$file_nome = $_FILES[$_POST['xoops_upload_file'][0]];
		$file_nome = (get_magic_quotes_gpc()) ? stripslashes($file_nome['name']) : $file_nome['name'];
		if(xoops_trim($file_nome!='')) {
			include_once(XOOPS_ROOT_PATH."/class/uploader.php");
			$uploader = new XoopsMediaUploader( MPU_HTML_PATH, $xoopsModuleConfig['mpu_conf_contentmimes'] , $xoopsModuleConfig['mpu_max_filesize']*1024);
			$uploader->setPrefix("page_");
			if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
				if ($uploader->upload()) {
					if (!empty($cfi_10_id)) {
						$contentfiles->deletaArquivo();
						if($contentfiles->getVar("cfi_30_arquivo") != ""){
						$mpu_classe = new mpu_mpb_mpublish();
						$mpu_classe->atualizaTodos("mpb_30_arquivo", $uploader->getSavedFileName(), new Criteria("mpb_30_arquivo", $contentfiles->getVar("cfi_30_arquivo")));
						}
					}
					$contentfiles->setVar("cfi_30_nome", $_POST['cfi_30_nome']);
					$contentfiles->setVar("cfi_30_arquivo", $uploader->getSavedFileName());
					$contentfiles->setVar("cfi_30_mime", $uploader->getMediaType());
					$contentfiles->setVar("cfi_10_tamanho", $uploader->getMediaSize());
					$contentfiles->setVar("cfi_12_exibir", $_POST['cfi_12_exibir']);
					$contentfiles->setVar("cfi_22_data", time());
					if(!$contentfiles->store()) {
						ob_start();
						xoops_error(MPU_ADM_PAGEERRORDB);
						$erro .= ob_get_clean();
					}else{
						redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php", 3,((empty($cfi_10_id)) ? MPU_ADM_SENFIL_SUCESS : MPU_ADM_SUCESS2));
					}
				} else {
					ob_start();
					xoops_error($uploader->getErrors(), MPU_ADM_SENDERROR);
					$erro .= ob_get_clean();
				}
			} else {
				ob_start();
				xoops_error($uploader->getErrors());
				$erro .= ob_get_clean();
			}
		}elseif ($file_nome == "" && !empty($cfi_10_id)){
			$contentfiles->setVar("cfi_30_nome", $_POST['cfi_30_nome']);
			$contentfiles->setVar("cfi_12_exibir", $_POST['cfi_12_exibir']);
			if(!$contentfiles->store()) {
				ob_start();
				xoops_error(MPU_ADM_PAGEERRORDB);
				$erro .= ob_get_clean();
			}else{
				redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php", 3,MPU_ADM_SUCESS2);
			}
		}else{
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php", 3,MPU_ADM_ERR_SELECT_FILE);
		}
	case 'contentfiles':
	default:
		mpu_adm_menu();
		echo (!empty($erro)) ? $erro."<br />" : '';
		$mpu_cfi_contentfiles = new mpu_cfi_contentfiles();
		$cfi_10_id = (empty($cfi_10_id)) ? null : $cfi_10_id;
		// Opções
		$c['op'] = 'contentfiles';
		$c['form'] = 0; // 0 para exibir os registros em modo visualização, 1 em modo edição
		$c['checks'] = 1;
		$c['print'] = 0;

		$c['nome'][1] = 'cfi_10_id';
		$c['rotulo'][1] = MPU_ADM_CFI_10_ID;
		$c['tipo'][1] = "text";
		$c['tamanho'][1] = 5;
		$c['show'][1] = '$reg->getVar($reg->id)';

		$c['nome'][2] = 'cfi_30_nome';
		$c['rotulo'][2] = MPU_ADM_CFI_30_NOME;
		$c['tipo'][2] = "text";
		$c['tamanho'][2] = 15;
		//$c['nosort'][2] = 1;

		$c['nome'][3] = 'cfi_30_arquivo';
		$c['rotulo'][3] = MPU_ADM_CFI_30_ARQUIVO;
		$c['tipo'][3] = "text";
		$c['show'][3] = '"<a href=\'".MPU_HTML_URL."/".$reg->getVar("cfi_30_arquivo")."\' target=\'_blank\'>".$reg->getVar("cfi_30_arquivo")."</a>"';
		$c['nosort'][3] = 1;

		$c['nome'][4] = 'cfi_10_tamanho';
		$c['rotulo'][4] = MPU_ADM_CFI_10_TAMANHO;
		$c['tipo'][4] = "none";
		$c['show'][4] = 'number_format($reg->getVar("cfi_10_tamanho")/1024, 2, ".", "")." Kb"';

		$c['nome'][5] = 'cfi_30_mime';
		$c['rotulo'][5] = MPU_ADM_CFI_30_MIME;
		$c['tipo'][5] = "select";
		$c['options'][5] = $mpu_cfi_contentfiles->pegaMimes();

		$c['nome'][6] = 'cfi_12_exibir';
		$c['rotulo'][6] = trim(MPU_ADM_EXIBIR);
		$c['tipo'][6] = "simnao";

		$c['nome'][7] = 'cfi_22_data';
		$c['rotulo'][7] = MPU_ADM_CFI_22_DATA;
		$c['tipo'][7] = "none";
		$c['show'][7] = 'date("d/m/Y", $reg->getVar("cfi_22_data"))';

		$c['group_del'] = 1;
		$c['group_del_function'][1] = 'deletaArquivo';

		$c['botoes'][1]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/paginas.php?op=contentfiles_editar';
		$c['botoes'][1]['imagem'] = 'images/editar.gif';
		$c['botoes'][1]['texto'] = _EDIT;

		$c['botoes'][2]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/paginas.php?op=contentfiles_deletar';
		$c['botoes'][2]['imagem'] = 'images/deletar.gif';
		$c['botoes'][2]['texto'] = _DELETE;

		// Tradução
		$c['lang']['titulo'] = MPU_ADM_PGTITULO;
		$c['lang']['filtros'] = MPU_ADM_FILTROS;
		$c['lang']['exibir'] = MPU_ADM_EXIBIR;
		$c['lang']['exibindo'] = MPU_ADM_EXIBINDO_FILES;
		$c['lang']['por_pagina'] = MPU_ADM_PORPAGINA;
		$c['lang']['acao'] = MPU_ADM_ACAO;
		$c['lang']['semresult'] = MPU_ADM_SEMRESULT;
		$c['lang']['group_action'] = MPU_ADM_GRP_ACTION;
		$c['lang']['group_erro_sel'] = MPU_ADM_GRP_ERR_SEL;
		$c['lang']['group_del'] = MPU_ADM_GRP_DEL;
		$c['lang']['group_del_sure'] = MPU_ADM_GRP_DEL_SURE;
		echo $mpu_cfi_contentfiles->administracao(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/paginas.php", $c);
		$cfi_classe =& new mpu_cfi_contentfiles($cfi_10_id);
		$form['titulo'] = ((empty($cfi_10_id)) ? MPU_ADM_NPG : MPU_ADM_EPG);
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/cfi.form.inc.php";
		$cfi_form->display();
		break;
}
echo "<div align='center'><a href='http://www.mastop.com.br/produtos/publish/'><img src='images/footer.gif'></a><br /><a style='color: #029116; font-size:11px' href='feedback.php'>".MPU_ADM_FEEDBACK."</a> - <a style='color: #FF0000; font-size:11px' href='http://www.mastop.com.br/produtos/publish/checkversion.php?lang=".$xoopsConfig['language']."&version=".round($xoopsModule->getVar('version') / 100, 2)."' target='_blank'>".MPU_ADM_CHKVERSION."</a></div>";
xoops_cp_footer();
?>
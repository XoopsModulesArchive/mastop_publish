<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo para manipulação de Arquivos
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once 'admin_header.php';
$op = (isset($_GET['op'])) ? $_GET['op'] : 'files';
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
	case "files_editar":
		mpu_adm_menu();
		$fil_10_id = (!empty($fil_10_id)) ? $fil_10_id : 0;
		$fil_classe =& new mpu_fil_files($fil_10_id);
		if (empty($fil_10_id) || $fil_classe->getVar('fil_10_id') == '') {
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/files.php", 3, MPU_ADM_ERRO_FIL404);
		}
		$form['titulo'] = MPU_ADM_EFILE;
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/fil.form.inc.php";
		$fil_form->display();
		break;
	case "files_deletar":
		mpu_adm_menu();
		$fil_10_id = (!empty($fil_10_id)) ? $fil_10_id : 0;
		$fil_classe =& new mpu_fil_files($fil_10_id);
		if (empty($fil_10_id) || $fil_classe->getVar('fil_10_id') == '') {
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/files.php", 3, MPU_ADM_ERRO_FIL404);
		}
		xoops_confirm(array('op' => 'files_deletar_ok', 'fil_10_id' => $fil_10_id), 'files.php', sprintf(MPU_ADM_CONFIRMA_DELFIL, $fil_10_id, $fil_classe->getVar("fil_30_nome")));
		break;
	case "files_deletar_ok":
		$fil_10_id = (!empty($fil_10_id)) ? $fil_10_id : 0;
		$fil_classe =& new mpu_fil_files($fil_10_id);
		if (empty($fil_10_id) || $fil_classe->getVar('fil_10_id') == '') {
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/files.php", 3, MPU_ADM_ERRO_FIL404);
		}
		$fil_classe->delete();
		$fil_classe->deletaArquivo();
		redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/files.php", 3,MPU_ADM_DELFIL_SUCESS);
		break;
	case 'files_adicionar':
		mpu_adm_menu();
		$fil_classe =& new mpu_fil_files();
		$form['titulo'] = MPU_ADM_NFILE;
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/fil.form.inc.php";
		$fil_form->display();
		break;
	case 'salvar':
		if (empty($fil_10_id)) {
			$files = new mpu_fil_files();
		}else{
			$files = new mpu_fil_files($fil_10_id);
		}
		$erro = '';
		$file_nome = $_FILES[$_POST['xoops_upload_file'][0]];
		$file_nome = (get_magic_quotes_gpc()) ? stripslashes($file_nome['name']) : $file_nome['name'];
		if(xoops_trim($file_nome!='')) {
			include_once(XOOPS_ROOT_PATH."/class/uploader.php");
			$uploader = new XoopsMediaUploader( MPU_FILES_PATH, $xoopsModuleConfig['mpu_conf_mimetypes'], $xoopsModuleConfig['mpu_max_filesize']*1024);
			$uploader->setPrefix("files_");
			if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
				if ($uploader->upload()) {
					if (!empty($fil_10_id)) {
						$files->deletaArquivo();
					}
					$files->setVar("fil_30_nome", $_POST['fil_30_nome']);
					$files->setVar("fil_30_arquivo", $uploader->getSavedFileName());
					$files->setVar("fil_30_mime", $uploader->getMediaType());
					$files->setVar("fil_10_tamanho", $uploader->getMediaSize());
					$files->setVar("fil_12_exibir", $_POST['fil_12_exibir']);
					$files->setVar("fil_22_data", time());
					if(!$files->store()) {
						ob_start();
						xoops_error(MPU_ADM_PAGEERRORDB);
						$erro .= ob_get_clean();
					}else{
						redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/files.php", 3,((empty($fil_10_id)) ? MPU_ADM_SENFIL_SUCESS : MPU_ADM_SUCESS2));
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
		}elseif ($file_nome == "" && !empty($fil_10_id)){
			$files->setVar("fil_30_nome", $_POST['fil_30_nome']);
			$files->setVar("fil_12_exibir", $_POST['fil_12_exibir']);
			if(!$files->store()) {
				ob_start();
				xoops_error(MPU_ADM_PAGEERRORDB);
				$erro .= ob_get_clean();
			}else{
				redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/files.php", 3,MPU_ADM_SUCESS2);
			}
		}else{
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/files.php", 3,MPU_ADM_ERR_SELECT_FILE);
		}
	case 'files':
	default:
		mpu_adm_menu();
		echo (!empty($erro)) ? $erro."<br />" : '';
		$mpu_fil_files = new mpu_fil_files();
		$fil_10_id = (empty($fil_10_id)) ? null : $fil_10_id;
		// Opções
		$c['op'] = 'files';
		$c['form'] = 0; // 0 para exibir os registros em modo visualização, 1 em modo edição
		$c['checks'] = 1;
		$c['print'] = 0;

		$c['nome'][1] = 'fil_10_id';
		$c['rotulo'][1] = MPU_ADM_FIL_10_ID;
		$c['tipo'][1] = "text";
		$c['tamanho'][1] = 5;
		$c['show'][1] = '$reg->getVar($reg->id)';

		$c['nome'][2] = 'fil_30_nome';
		$c['rotulo'][2] = MPU_ADM_FIL_30_NOME;
		$c['tipo'][2] = "text";
		$c['tamanho'][2] = 15;
		//$c['nosort'][2] = 1;

		$c['nome'][3] = 'fil_30_arquivo';
		$c['rotulo'][3] = MPU_ADM_FIL_30_ARQUIVO;
		$c['tipo'][3] = "text";
		$c['show'][3] = '"<a href=\'".MPU_FILES_URL."/".$reg->getVar("fil_30_arquivo")."\' target=\'_blank\'>".$reg->getVar("fil_30_arquivo")."</a>"';
		$c['nosort'][3] = 1;

		$c['nome'][4] = 'fil_10_tamanho';
		$c['rotulo'][4] = MPU_ADM_FIL_10_TAMANHO;
		$c['tipo'][4] = "none";
		$c['show'][4] = 'number_format($reg->getVar("fil_10_tamanho")/1024, 2, ".", "")." Kb"';

		$c['nome'][5] = 'fil_30_mime';
		$c['rotulo'][5] = MPU_ADM_FIL_30_MIME;
		$c['tipo'][5] = "select";
		$c['options'][5] = $mpu_fil_files->pegaMimes();

		$c['nome'][6] = 'fil_12_exibir';
		$c['rotulo'][6] = trim(MPU_ADM_EXIBIR);
		$c['tipo'][6] = "simnao";

		$c['nome'][7] = 'fil_22_data';
		$c['rotulo'][7] = MPU_ADM_FIL_22_DATA;
		$c['tipo'][7] = "none";
		$c['show'][7] = 'date("d/m/Y", $reg->getVar("fil_22_data"))';

		$c['group_del'] = 1;
		$c['group_del_function'][1] = 'deletaArquivo';

		$c['botoes'][1]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/files.php?op=files_editar';
		$c['botoes'][1]['imagem'] = 'images/editar.gif';
		$c['botoes'][1]['texto'] = _EDIT;

		$c['botoes'][2]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/files.php?op=files_deletar';
		$c['botoes'][2]['imagem'] = 'images/deletar.gif';
		$c['botoes'][2]['texto'] = _DELETE;

		// Tradução
		$c['lang']['titulo'] = MPU_ADM_FILETITULO;
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
		echo $mpu_fil_files->administracao(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/files.php", $c);
		$fil_classe =& new mpu_fil_files($fil_10_id);
		$form['titulo'] = ((empty($fil_10_id)) ? MPU_ADM_NFILE : MPU_ADM_EFILE);
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/fil.form.inc.php";
		$fil_form->display();
		break;
}
echo "<div align='center'><a href='http://www.mastop.com.br/produtos/publish/'><img src='images/footer.gif'></a><br /><a style='color: #029116; font-size:11px' href='feedback.php'>".MPU_ADM_FEEDBACK."</a> - <a style='color: #FF0000; font-size:11px' href='http://www.mastop.com.br/produtos/publish/checkversion.php?lang=".$xoopsConfig['language']."&version=".round($xoopsModule->getVar('version') / 100, 2)."' target='_blank'>".MPU_ADM_CHKVERSION."</a></div>";
xoops_cp_footer();
?>
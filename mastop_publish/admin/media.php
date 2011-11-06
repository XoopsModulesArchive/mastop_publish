<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo para manipulação de Mídias
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once 'admin_header.php';
$op = (isset($_GET['op'])) ? $_GET['op'] : 'media';
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
	case "media_editar":
		mpu_adm_menu();
		$med_10_id = (!empty($med_10_id)) ? $med_10_id : 0;
		$med_classe =& new mpu_med_media($med_10_id);
		if (empty($med_10_id) || $med_classe->getVar('med_10_id') == '') {
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/media.php", 3, MPU_ADM_ERRO_MED404);
		}
		$form['titulo'] = MPU_ADM_EMEDIA;
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/med.form.inc.php";
		$med_form->display();
		break;
	case "media_deletar":
		mpu_adm_menu();
		$med_10_id = (!empty($med_10_id)) ? $med_10_id : 0;
		$med_classe =& new mpu_med_media($med_10_id);
		if (empty($med_10_id) || $med_classe->getVar('med_10_id') == '') {
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/media.php", 3, MPU_ADM_ERRO_MED404);
		}
		xoops_confirm(array('op' => 'media_deletar_ok', 'med_10_id' => $med_10_id), 'media.php', sprintf(MPU_ADM_CONFIRMA_DELMED, $med_10_id, $med_classe->getVar("med_30_nome")));
		break;
	case "media_deletar_ok":
		$med_10_id = (!empty($med_10_id)) ? $med_10_id : 0;
		$med_classe =& new mpu_med_media($med_10_id);
		if (empty($med_10_id) || $med_classe->getVar('med_10_id') == '') {
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/media.php", 3, MPU_ADM_ERRO_MED404);
		}
		$med_classe->delete();
		$med_classe->deletaArquivo();
		redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/media.php", 3,MPU_ADM_DELMED_SUCESS);
		break;
	case 'media_adicionar':
		mpu_adm_menu();
		$med_classe =& new mpu_med_media();
		$form['titulo'] = MPU_ADM_NMEDIA;
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/med.form.inc.php";
		$med_form->display();
		break;
	case 'salvar':
		if (empty($med_10_id)) {
			$media = new mpu_med_media();
		}else{
			$media = new mpu_med_media($med_10_id);
		}
		$erro = '';
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
					if (!empty($med_10_id)) {
						$media->deletaArquivo();
					}
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
						ob_start();
						xoops_error(MPU_ADM_PAGEERRORDB);
						$erro .= ob_get_clean();

					}else{
						redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/media.php", 3,((empty($med_10_id)) ? MPU_ADM_SENMED_SUCESS : MPU_ADM_SUCESS2));
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
		}elseif ($file_nome == "" && !empty($med_10_id)){
			$media->setVar("med_30_nome", $_POST['med_30_nome']);
			$media->setVar("med_10_altura", $_POST['med_10_altura']);
			$media->setVar("med_10_largura", $_POST['med_10_largura']);
			$media->setVar("med_12_exibir", $_POST['med_12_exibir']);
			if(!$media->store()) {
				ob_start();
				xoops_error(MPU_ADM_PAGEERRORDB);
				$erro .= ob_get_clean();

			}else{
				redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/media.php", 3,MPU_ADM_SUCESS2);
			}
		}else{
			redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/media.php", 3,MPU_ADM_ERR_SELECT_MEDIA);
		}
	case 'media':
	default:
		mpu_adm_menu();
		echo (!empty($erro)) ? $erro."<br />" : '';
		$mpu_med_media = new mpu_med_media();
		$med_10_id = (empty($med_10_id)) ? null : $med_10_id;
		// Opções
		$c['op'] = 'media';
		$c['form'] = 0; // 0 para exibir os registros em modo visualização, 1 em modo edição
		$c['checks'] = 1;

		$c['nome'][1] = 'med_10_id';
		$c['rotulo'][1] = MPU_ADM_MED_10_ID;
		$c['tipo'][1] = "text";
		$c['tamanho'][1] = 5;
		$c['show'][1] = '$reg->getVar($reg->id)';

		$c['nome'][2] = 'med_30_nome';
		$c['rotulo'][2] = MPU_ADM_MED_30_NOME;
		$c['tipo'][2] = "text";
		$c['tamanho'][2] = 15;
		//$c['nosort'][2] = 1;

		$c['nome'][3] = 'med_30_arquivo';
		$c['rotulo'][3] = MPU_ADM_MED_30_ARQUIVO;
		$c['tipo'][3] = "text";
		$c['show'][3] = '"<a href=\'".MPU_MEDIA_URL."/".$reg->getVar("med_30_arquivo")."\' target=\'_blank\'>".$reg->getVar("med_30_arquivo")."</a>"';
		$c['nosort'][3] = 1;

		$c['nome'][4] = 'med_10_tamanho';
		$c['rotulo'][4] = MPU_ADM_MED_10_TAMANHO;
		$c['tipo'][4] = "none";
		$c['show'][4] = 'number_format($reg->getVar("med_10_tamanho")/1024, 2, ".", "")." Kb"';

		$c['nome'][5] = 'med_10_tipo';
		$c['rotulo'][5] = MPU_ADM_MED_10_TIPO;
		$c['tipo'][5] = "select";
		$c['options'][5] = array(1 => MPU_ADM_MED_10_TIPO_1, 2=>MPU_ADM_MED_10_TIPO_2, 3=>MPU_ADM_MED_10_TIPO_3, 4=>MPU_ADM_MED_10_TIPO_4, 5=>MPU_ADM_MED_10_TIPO_5);

		$c['nome'][6] = 'med_12_exibir';
		$c['rotulo'][6] = trim(MPU_ADM_EXIBIR);
		$c['tipo'][6] = "simnao";

		$c['nome'][7] = 'med_22_data';
		$c['rotulo'][7] = MPU_ADM_MED_22_DATA;
		$c['tipo'][7] = "none";
		$c['show'][7] = 'date("d/m/Y", $reg->getVar("med_22_data"))';

		$c['group_del'] = 1;
		$c['group_del_function'][1] = 'deletaArquivo';

		$c['botoes'][1]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/media.php?op=media_editar';
		$c['botoes'][1]['imagem'] = 'images/editar.gif';
		$c['botoes'][1]['texto'] = MPU_ADM_EMEDIA;

		$c['botoes'][2]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/media.php?op=media_deletar';
		$c['botoes'][2]['imagem'] = 'images/deletar.gif';
		$c['botoes'][2]['texto'] = MPU_ADM_DMEDIA;

		// Tradução
		$c['lang']['titulo'] = MPU_ADM_MEDIATITULO;
		$c['lang']['filtros'] = MPU_ADM_FILTROS;
		$c['lang']['exibir'] = MPU_ADM_EXIBIR;
		$c['lang']['exibindo'] = MPU_ADM_EXIBINDO_MEDIA;
		$c['lang']['por_pagina'] = MPU_ADM_PORPAGINA;
		$c['lang']['acao'] = MPU_ADM_ACAO;
		$c['lang']['semresult'] = MPU_ADM_SEMRESULT;
		$c['lang']['group_action'] = MPU_ADM_GRP_ACTION;
		$c['lang']['group_erro_sel'] = MPU_ADM_GRP_ERR_SEL;
		$c['lang']['group_del'] = MPU_ADM_GRP_DEL;
		$c['lang']['group_del_sure'] = MPU_ADM_GRP_DEL_SURE;
		echo $mpu_med_media->administracao(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/media.php", $c);
		$med_classe =& new mpu_med_media($med_10_id);
		$form['titulo'] = ((empty($med_10_id)) ? MPU_ADM_NMEDIA : MPU_ADM_EMEDIA);
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/med.form.inc.php";
		$med_form->display();
		break;
}
echo "<div align='center'><a href='http://www.mastop.com.br/produtos/publish/'><img src='images/footer.gif'></a><br /><a style='color: #029116; font-size:11px' href='feedback.php'>".MPU_ADM_FEEDBACK."</a> - <a style='color: #FF0000; font-size:11px' href='http://www.mastop.com.br/produtos/publish/checkversion.php?lang=".$xoopsConfig['language']."&version=".round($xoopsModule->getVar('version') / 100, 2)."' target='_blank'>".MPU_ADM_CHKVERSION."</a></div>";
xoops_cp_footer();
?>
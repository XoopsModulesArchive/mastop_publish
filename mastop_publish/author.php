<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Página onde os autores podem editar, criar sub pages, etc
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include '../../mainfile.php';
include_once "header.php";
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_cfi_contentfiles.class.php";
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
if ( file_exists( "language/" . $xoopsConfig['language'] . "/admin.php" ) ) {
	include_once "language/" . $xoopsConfig['language'] . "/admin.php";
}
elseif ( file_exists( "language/portuguesebr/admin.php" ) ) {
	include_once "language/portuguesebr/admin.php";
}
if (empty($op)) {
	$op = "listar";
}elseif ($op == "editar" || $op == "limpacont" || $op == "limpacont_ok" || $op == "novo"){
	$mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
	$mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
	if (empty($xoopsUser) || empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '' || $mpu_classe->getVar('usr_10_uid') != $xoopsUser->getVar('uid')) {
		redirect_header(XOOPS_URL, 3, MPU_ADM_403);
	}
}
switch ($op){
	case "limpacont":
		xoops_confirm(array('op' => 'limpacont_ok', 'mpb_10_id' => $mpb_10_id), 'author.php', sprintf(MPU_ADM_CONFIRMA_LIMPACONT, $mpb_10_id, $mpu_classe->getVar("mpb_30_menu")));
		break;
	case "limpacont_ok":
		$mpu_classe->setVar("mpb_10_contador", 0);
		$mpu_classe->setVar("mpb_22_atualizado", time());
		if (!$mpu_classe->store()) {
			redirect_header(XOOPS_URL, 3, MPU_ADM_ERRO1);
		}else{
			redirect_header($mpu_classe->pegaLink(), 3, MPU_ADM_SUCESS1);
		}
		break;
	case "novo":
		if (!$xoopsModuleConfig['mpu_conf_cancreate']) {
			redirect_header(XOOPS_URL, 3, MPU_ADM_403);
		}
		$mpb_10_idpai = $mpb_10_id;
		$mpb_10_id = null;
		$mpu_classe =& new mpu_mpb_mpublish();
		$cfi_classe =& new mpu_cfi_contentfiles();
		$form['titulo'] = MPU_ADM_NOVO;
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/mpb.form.author.inc.php";
		$mpb_form->display();
		break;
	case "salvar":
		if (!empty($mpb_10_idpai) && empty($mpb_10_id)) {
			$mpu_classe_pai = new mpu_mpb_mpublish($mpb_10_idpai);
			if (empty($xoopsUser) || $mpu_classe_pai->getVar("usr_10_uid") != $xoopsUser->getVar('uid') || !$xoopsModuleConfig['mpu_conf_cancreate']) {
				redirect_header(XOOPS_URL, 3, MPU_ADM_403);
			}
		}
		$mpu_classe = (isset($mpb_10_id) && $mpb_10_id>0) ? new mpu_mpb_mpublish($mpb_10_id) : new mpu_mpb_mpublish();
		if (($mpu_classe->getVar("mpb_10_id") != "" && !$xoopsModuleConfig['mpu_conf_canedit']) || ($mpu_classe->getVar("mpb_10_id") == "" && !$xoopsModuleConfig['mpu_conf_cancreate']) || ($mpu_classe->getVar("mpb_10_id") != "" && $mpu_classe->getVar("usr_10_uid") != $xoopsUser->getVar('uid'))) {
			redirect_header(XOOPS_URL, 3, MPU_ADM_403);
		}
		$mpu_classe->setVar('mpb_10_idpai', $mpb_10_idpai);
		if(empty($mpb_10_id)) $mpu_classe->setVar('usr_10_uid', $xoopsUser->getVar('uid'));
		$mpu_classe->setVar('mpb_30_menu', $mpb_30_menu);
		$mpu_classe->setVar('mpb_30_titulo', $mpb_30_titulo);
		$mpu_classe->setVar('mpb_35_conteudo', ((isset($mpb_12_semlink) || isset($mpb_frame) || isset($mpb_pagina) || empty($mpb_35_conteudo)) ? '' : $mpb_35_conteudo));
		$mpu_classe->setVar('mpb_12_semlink', ((isset($mpb_12_semlink)) ? 1 : 0));
		$mpb_30_arquivo = (!empty($_POST['mpb_30_arquivo'])) ? $_POST['mpb_30_arquivo'] : ((!empty($_POST['pagina'])) ? $_POST['pagina'] : "");
		$mpu_classe->setVar('mpb_30_arquivo', ((isset($mpb_12_semlink)) ? '' : ((isset($mpb_frame)) ? $mpb_30_arquivo_frame : ((isset($mpb_external)) ? "ext:".$mpb_30_arquivo_external : $mpb_30_arquivo))));
		$mpu_classe->setVar('mpb_11_visivel', $mpb_11_visivel);
		$mpu_classe->setVar('mpb_11_abrir', $mpb_11_abrir);
		$mpu_classe->setVar('mpb_12_comentarios', ((isset($mpb_12_comentarios)) ? 1 : 0));
		$mpu_classe->setVar('mpb_12_exibesub', ((isset($mpb_12_exibesub)) ? 1 : 0));
		$mpu_classe->setVar('mpb_12_recomendar', ((isset($mpb_12_recomendar)) ? 1 : 0));
		$mpu_classe->setVar('mpb_12_imprimir', ((isset($mpb_12_imprimir)) ? 1 : 0));
		if (empty($mpb_10_id)) $mpu_classe->setVar('mpb_22_criado', time());
		$mpu_classe->setVar('mpb_22_atualizado', time());
		$mpu_classe->setVar('mpb_10_ordem', ((isset($mpb_10_ordem) && $mpb_10_ordem > 0) ? (int)$mpb_10_ordem : 0));
		$mpu_classe->setVar('mpb_10_contador', (($mpu_classe->getVar('mpb_10_contador') > 0) ? $mpu_classe->getVar('mpb_10_contador')+1 : 0));
		if (!$mpu_classe->store()) {
			redirect_header(XOOPS_URL, 3, MPU_ADM_ERRO1);
		}else{
			if((isset($mpb_10_id) && $mpb_10_id>0)) mpu_apagaPermissoes($mpb_10_id);
			$grupos_ids = $xoopsUser->getGroups();
			if (!in_array(XOOPS_GROUP_ADMIN, $grupos_ids)) {
				array_push($grupos_ids, XOOPS_GROUP_ADMIN);
			}
			if (!in_array(XOOPS_GROUP_ANONYMOUS, $grupos_ids)) {
				array_push($grupos_ids, XOOPS_GROUP_ANONYMOUS);
			}
			if( !empty($grupos_ids) && count($grupos_ids) > 0 ){
				mpu_inserePermissao($mpu_classe->getVar("mpb_10_id"),$grupos_ids);
			}
			redirect_header($mpu_classe->pegaLink(), 3, MPU_ADM_SUCESS1);
		}
	case "editar":
		if (!$xoopsModuleConfig['mpu_conf_canedit']) {
			redirect_header(XOOPS_URL, 3, MPU_ADM_403);
		}
		$mpb_10_idpai = $mpu_classe->getVar('mpb_10_idpai');
		$cfi_classe =& new mpu_cfi_contentfiles();
		$form['titulo'] = MPU_ADM_EPAGE;
		$form['op'] = "salvar";
		include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/mpb.form.author.inc.php";
		$mpb_form->display();
		break;
	case "deletar":
		$mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
		$mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
		if (empty($xoopsUser) || empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '' || $mpu_classe->getVar('usr_10_uid') != $xoopsUser->getVar('uid') || !$xoopsModuleConfig['mpu_conf_candelete']) {
			redirect_header(XOOPS_URL, 3, MPU_ADM_403);
		}
		if($mpu_classe->tem_subcategorias()){
			xoops_confirm(array('op' => 'deletar_ok', 'mpb_10_id' => $mpb_10_id), 'author.php', sprintf(MPU_ADM_CONFIRMA_DEL_SUB, $mpb_10_id, $mpu_classe->getVar("mpb_30_menu"), $mpu_classe->contar(new Criteria("mpb_10_idpai", $mpb_10_id))));
		}else{
			xoops_confirm(array('op' => 'deletar_ok', 'mpb_10_id' => $mpb_10_id), 'author.php', sprintf(MPU_ADM_CONFIRMA_DEL, $mpb_10_id, $mpu_classe->getVar("mpb_30_menu")));
		}
		break;
	case "deletar_ok":
		$mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
		$mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
		if (empty($xoopsUser) || empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '' || $mpu_classe->getVar('usr_10_uid') != $xoopsUser->getVar('uid') || !$xoopsModuleConfig['mpu_conf_candelete']) {
			redirect_header(XOOPS_URL, 3, MPU_ADM_403);
		}
		$mpu_total_deletados = 0;
		mpu_apagaPermissoes($mpb_10_id);
		$mpu_classe->delete();
		$mpu_total_deletados += $mpu_classe->afetadas;
		if($mpu_classe->tem_subcategorias()){
			mpu_apagaPermissoesPai($mpb_10_id);
			$mpu_classe->deletaTodos(new Criteria("mpb_10_idpai", $mpb_10_id));
			$mpu_total_deletados += $mpu_classe->afetadas;
		}
		redirect_header($_SERVER['PHP_SELF']."?op=listar", 3,sprintf(MPU_ADM_DEL_SUCESS, $mpu_total_deletados));
		break;
	case "listar":
	default:
		$mpu_classe = new mpu_mpb_mpublish();
		$criterio = null;
		// Opções
		$c['op'] = 'listar';
		$c['form'] = 0; // 0 para exibir os registros em modo visualização, 1 em modo edição
		$c['precrit']['campo'][1] = "usr_10_uid";
		$c['precrit']['valor'][1] = $xoopsUser->getVar('uid');
		$c['nome'][1] = 'mpb_10_id';
		$c['rotulo'][1] = MPU_ADM_MPB_10_ID;
		$c['tipo'][1] = "text";
		$c['tamanho'][1] = 5;
		$c['show'][1] = '"<a href=\'".$reg->pegaLink()."\' target=\'_blank\'>".$reg->getVar($reg->id)."</a>"';

		$c['nome'][2] = 'mpb_10_idpai';
		$c['rotulo'][2] = MPU_ADM_MPB_10_IDPAI;
		$c['tipo'][2] = "select";
		$c['options'][2] = $mpu_classe->geraMenuSelect();

		$c['nome'][3] = 'mpb_30_menu';
		$c['rotulo'][3] = MPU_ADM_MPB_30_MENU;
		$c['tipo'][3] = "text";
		$c['tamanho'][3] = 15;

		$c['nome'][4] = 'mpb_30_titulo';
		$c['rotulo'][4] = MPU_ADM_MPB_30_TITULO;
		$c['tipo'][4] = "text";

		$c['nome'][5] = 'mpb_11_visivel';
		$c['rotulo'][5] = MPU_ADM_MPB_11_VISIVEL;
		$c['tipo'][5] = "select";
		$c['options'][5] = array(1=>MPU_ADM_MPB_11_VISIVEL_1, 3=>MPU_ADM_MPB_11_VISIVEL_3, 2=>MPU_ADM_MPB_11_VISIVEL_2, 4=>MPU_ADM_MPB_11_VISIVEL_4);

		$c['nome'][6] = 'mpb_10_ordem';
		$c['rotulo'][6] = MPU_ADM_MPB_10_ORDEM;
		$c['tipo'][6] = "text";
		$c['tamanho'][6] = 3;

		$c['nome'][7] = 'mpb_10_contador';
		$c['rotulo'][7] = MPU_ADM_MPB_10_CONTADOR;
		$c['tipo'][7] = "none";

		if ($xoopsModuleConfig['mpu_conf_cancreate']) {
			$c['botoes'][1]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/author.php?op=novo';
			$c['botoes'][1]['imagem'] = 'images/novo.gif';
			$c['botoes'][1]['texto'] = MPU_ADM_NOVO;
		}
		if ($xoopsModuleConfig['mpu_conf_canedit']) {
			$c['botoes'][2]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/author.php?op=editar';
			$c['botoes'][2]['imagem'] = 'images/editar.gif';
			$c['botoes'][2]['texto'] = _EDIT;
		}

		if ($xoopsModuleConfig['mpu_conf_candelete']) {
			$c['botoes'][3]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/author.php?op=deletar';
			$c['botoes'][3]['imagem'] = 'images/deletar.gif';
			$c['botoes'][3]['texto'] = _DELETE;
		}
		// Tradução
		$c['lang']['titulo'] = MPU_ADM_TITULO;
		$c['lang']['filtros'] = MPU_ADM_FILTROS;
		$c['lang']['exibir'] = MPU_ADM_EXIBIR;
		$c['lang']['exibindo'] = MPU_ADM_EXIBINDO;
		$c['lang']['por_pagina'] = MPU_ADM_PORPAGINA;
		$c['lang']['acao'] = MPU_ADM_ACAO;
		$c['lang']['semresult'] = MPU_ADM_SEMRESULT;
		echo $mpu_classe->administracao(XOOPS_URL."/modules/".MPU_MOD_DIR."/author.php", $c);
}
echo "<div align='center'><a href='http://www.mastop.com.br/produtos/publish/'><img src='admin/images/footer.gif'></a></div>";
include_once XOOPS_ROOT_PATH.'/footer.php';
exit;
?>
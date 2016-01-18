<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Admin do Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once 'admin_header.php';
$op = (isset($_GET['op'])) ? $_GET['op'] : 'listar';
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
switch ($op){
    case "limpacont":
        $mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
        $mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
        if (empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '') {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO2);
        }
        mpu_adm_menu();
        xoops_confirm(array('op' => 'limpacont_ok', 'mpb_10_id' => $mpb_10_id), 'index.php', sprintf(MPU_ADM_CONFIRMA_LIMPACONT, $mpb_10_id, $mpu_classe->getVar("mpb_30_menu")));
        break;

    case "limpacont_ok":
        $mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
        $mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
        if (empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '') {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO2);
        }
        $mpu_classe->setVar("mpb_10_contador", 0);
        $mpu_classe->setVar("mpb_22_atualizado", time());
        if (!$mpu_classe->store()) {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO1);
        }else{
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php?op=listar_editar&mpb_10_id=".$mpb_10_id, 3, MPU_ADM_SUCESS1);
        }
        break;
    case "novo":
        mpu_adm_menu();
        $mpb_10_id = null;
        $mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
        $cfi_classe =& new mpu_cfi_contentfiles();
        $form['titulo'] = MPU_ADM_NOVO;
        $form['op'] = "salvar";
        include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/mpb.form.inc.php";
        $mpb_form->display();
        break;
    case "salvar":
        $mpu_classe = (isset($mpb_10_id) && $mpb_10_id>0) ? new mpu_mpb_mpublish($mpb_10_id) : new mpu_mpb_mpublish();
        $mpu_classe->setVar('mpb_10_idpai', $mpb_10_idpai);
        $mpu_classe->setVar('usr_10_uid', (empty($usr_10_uid) ? $xoopsUser->getVar('uid') : $usr_10_uid));
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
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO1);
        }else{
            if((isset($mpb_10_id) && $mpb_10_id>0)) mpu_apagaPermissoes($mpb_10_id);
            if( !empty($grupos_perm) && count($grupos_perm) > 0 ){
                mpu_inserePermissao($mpu_classe->getVar("mpb_10_id"),$grupos_perm);
            }
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_SUCESS1);
        }
        break;
    case "listar_clone":
        $mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
        $mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
        if (empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '') {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO2);
        }
        mpu_adm_menu();
        xoops_confirm(array('op' => 'listar_clone_ok', 'mpb_10_id' => $mpb_10_id), 'index.php', sprintf(MPU_ADM_CONFIRMA_CLONE, $mpb_10_id, $mpu_classe->getVar("mpb_30_menu")));
        break;
    case "listar_clone_ok":
        $mpu_classe = (isset($mpb_10_id) && $mpb_10_id>0) ? new mpu_mpb_mpublish($mpb_10_id) : new mpu_mpb_mpublish();
        $grupos_ids = $moduleperm_handler->getGroupIds("mpu_mpublish_acesso", $mpb_10_id, $xoopsModule->getVar('mid'));
        if ($mpu_classe->getVar("mpb_10_id") > 0) {
            $mpu_classe->setVar('mpb_10_id', 0);
            $mpu_classe->setVar('mpb_30_menu', MPU_ADM_CLONE.$mpu_classe->getVar("mpb_30_menu"));
            if (!$mpu_classe->store()) {
                redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO1);
            }else{
                mpu_inserePermissao($mpu_classe->getVar("mpb_10_id"),$grupos_ids);
                redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_SUCESS1);
            }
        }else{
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO2);
        }
        break;
    case "listar_editar":
        mpu_adm_menu();
        $mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
        $mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
        $cfi_classe =& new mpu_cfi_contentfiles();
        if (empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '') {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO2);
        }
        $form['titulo'] = MPU_ADM_EPAGE;
        $form['op'] = "salvar";
        include XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/include/mpb.form.inc.php";
        $mpb_form->display();
        break;
    case "listar_deletar":
        $mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
        $mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
        if (empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '') {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO2);
        }
        if($mpu_classe->tem_subcategorias()){
            mpu_adm_menu();
            xoops_confirm(array('op' => 'listar_deletar_ok', 'mpb_10_id' => $mpb_10_id), 'index.php', sprintf(MPU_ADM_CONFIRMA_DEL_SUB, $mpb_10_id, $mpu_classe->getVar("mpb_30_menu"), $mpu_classe->contar(new Criteria("mpb_10_idpai", $mpb_10_id))));
        }else{
            mpu_adm_menu();
            xoops_confirm(array('op' => 'listar_deletar_ok', 'mpb_10_id' => $mpb_10_id), 'index.php', sprintf(MPU_ADM_CONFIRMA_DEL, $mpb_10_id, $mpu_classe->getVar("mpb_30_menu")));
        }
        break;
    case "listar_deletar_ok":
        $mpb_10_id = (!empty($mpb_10_id)) ? $mpb_10_id : 0;
        $mpu_classe =& new mpu_mpb_mpublish($mpb_10_id);
        if (empty($mpb_10_id) || $mpu_classe->getVar('mpb_10_id') == '') {
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3, MPU_ADM_ERRO2);
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
        redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", 3,sprintf(MPU_ADM_DEL_SUCESS, $mpu_total_deletados));
        break;
    case "listar_all":
        if(empty($_POST['campos'])){
            mpu_adm_menu();
            $mpu_classe = new mpu_mpb_mpublish();
            $criterio = null;
            // Opções
            $c['op'] = 'listar_all';
            $c['form'] = 1; // 0 para exibir os registros em modo visualização, 1 em modo edição
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

            $c['botoes'][1]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/index.php?op=listar_editar';
            $c['botoes'][1]['imagem'] = 'images/editar.gif';
            $c['botoes'][1]['texto'] = _EDIT;

            $c['botoes'][2]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/index.php?op=listar_deletar';
            $c['botoes'][2]['imagem'] = 'images/deletar.gif';
            $c['botoes'][2]['texto'] = _DELETE;

            // Tradução
            $c['lang']['titulo'] = MPU_ADM_TITULO;
            $c['lang']['filtros'] = MPU_ADM_FILTROS;
            $c['lang']['exibir'] = MPU_ADM_EXIBIR;
            $c['lang']['exibindo'] = MPU_ADM_EXIBINDO;
            $c['lang']['por_pagina'] = MPU_ADM_PORPAGINA;
            $c['lang']['acao'] = MPU_ADM_ACAO;
            $c['lang']['semresult'] = MPU_ADM_SEMRESULT;
            echo $mpu_classe->administracao(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", $c);
            echo "<a href='".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php?op=listar' style='padding:3px; border:5px double #FFFFFF; background-color: #00950F; color: #FFFFFF'>".MPU_ADM_SHOW_NESTED_MODE."</a>";
        }else{
            foreach ($_POST['campos'] as $k=>$v) {
                $mpu_classe = new mpu_mpb_mpublish($k);
                $mpu_classe->assignVars($v);
                $mpu_classe->store();
            }
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php?op=listar_all", 3, MPU_ADM_SUCESS2);
        }
        break;
    case "listar":
    default:
        if(empty($_POST['campos'])){
            mpu_adm_menu();
            $criterio = null;
            if (isset($_REQUEST['mpb_10_id'])) {
                $mpb_10_id = $_REQUEST['mpb_10_id'];
                $_SESSION['listar_mpu_mpb_10_id'] = $_REQUEST['mpb_10_id'];
                $mpu_classe = new mpu_mpb_mpublish($mpb_10_id);
            }elseif (!empty($_SESSION['listar_mpu_mpb_10_id'])){
                $mpb_10_id = $_SESSION['listar_mpu_mpb_10_id'];
                $mpu_classe = new mpu_mpb_mpublish($mpb_10_id);
            }else{
                $mpb_10_id = 0;
                $mpu_classe = new mpu_mpb_mpublish();
            }
            // Opções
            $c['op'] = 'listar';
            $c['form'] = 1; // 0 para exibir os registros em modo visualização, 1 em modo edição
            $c['precrit']['campo'][1] = "mpb_10_idpai";
            $c['precrit']['valor'][1] = $mpb_10_id;
            if ($mpb_10_id == 0) {
                $c['precrit']['operador'][1] = "<=";
            }

            $c['nome'][1] = 'mpb_10_id';
            $c['rotulo'][1] = MPU_ADM_MPB_10_ID;
            $c['tipo'][1] = "text";
            $c['tamanho'][1] = 5;
            $c['show'][1] = '"<a href=\'".$reg->pegaLink()."\' target=\'_blank\'>".$reg->getVar($reg->id)."</a>"';

            $c['nome'][2] = 'mpb_30_menu';
            $c['rotulo'][2] = MPU_ADM_MPB_30_MENU;
            $c['tipo'][2] = "text";
            $c['tamanho'][2] = 15;

            $c['nome'][3] = 'mpb_30_titulo';
            $c['rotulo'][3] = MPU_ADM_MPB_30_TITULO;
            $c['tipo'][3] = "text";

            $c['nome'][4] = 'mpb_11_visivel';
            $c['rotulo'][4] = MPU_ADM_MPB_11_VISIVEL;
            $c['tipo'][4] = "select";
            $c['options'][4] = array(1=>MPU_ADM_MPB_11_VISIVEL_1, 3=>MPU_ADM_MPB_11_VISIVEL_3, 2=>MPU_ADM_MPB_11_VISIVEL_2, 4=>MPU_ADM_MPB_11_VISIVEL_4);

            $c['nome'][5] = 'mpb_10_ordem';
            $c['rotulo'][5] = MPU_ADM_MPB_10_ORDEM;
            $c['tipo'][5] = "text";
            $c['tamanho'][5] = 3;

            $c['nome'][6] = 'subpages';
            $c['rotulo'][6] = MPU_ADM_SUBS;
            $c['tipo'][6] = "none";
            $c['show'][6] = '($mySubs = $reg->countSubs()) ? $mySubs." <a href=\''.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/index.php?op=listar&mpb_10_id=".$reg->getVar($reg->id)."'.'\' title=\''.MPU_ADM_SHOWSUBS.'\'><img src=\'images/view.gif\' align=\'absmiddle\' alt=\''.MPU_ADM_SHOWSUBS.'\'></a>": 0;';
            $c['nosort'][6] = 1;

            $c['nome'][7] = 'mpb_10_contador';
            $c['rotulo'][7] = MPU_ADM_MPB_10_CONTADOR;
            $c['tipo'][7] = "none";

            $c['botoes'][1]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/index.php?op=listar_editar';
            $c['botoes'][1]['imagem'] = 'images/editar.gif';
            $c['botoes'][1]['texto'] = _EDIT;

            $c['botoes'][2]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/index.php?op=listar_clone';
            $c['botoes'][2]['imagem'] = 'images/clone.gif';
            $c['botoes'][2]['texto'] = _CLONE;

            $c['botoes'][3]['link'] = XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/admin/index.php?op=listar_deletar';
            $c['botoes'][3]['imagem'] = 'images/deletar.gif';
            $c['botoes'][3]['texto'] = _DELETE;

            // Tradução
            $c['lang']['titulo'] = MPU_ADM_TITULO."<br /><div style='font-weight:normal; font-size:11px'>".$mpu_classe->geraNavigationAdmin($mpb_10_id)."</div>";
            $c['lang']['filtros'] = MPU_ADM_FILTROS;
            $c['lang']['exibir'] = MPU_ADM_EXIBIR;
            $c['lang']['exibindo'] = MPU_ADM_EXIBINDO;
            $c['lang']['por_pagina'] = MPU_ADM_PORPAGINA;
            $c['lang']['acao'] = MPU_ADM_ACAO;
            $c['lang']['semresult'] = MPU_ADM_SEMRESULT;
            echo $mpu_classe->administracao(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php", $c);
            echo "<a href='".XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php?op=listar_all' style='padding:3px; border:5px double #FFFFFF; background-color: #00950F; color: #FFFFFF'>".MPU_ADM_SHOW_INLINE_MODE."</a>";
        }else{
            foreach ($_POST['campos'] as $k=>$v) {
                $mpu_classe = new mpu_mpb_mpublish($k);
                $mpu_classe->assignVars($v);
                $mpu_classe->store();
            }
            redirect_header(XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php?op=listar", 3, MPU_ADM_SUCESS2);
        }

}
echo "<div align='center'><a href='http://www.mastop.com.br/produtos/publish/'><img src='images/footer.gif'></a><br /><a style='color: #029116; font-size:11px' href='feedback.php'>".MPU_ADM_FEEDBACK."</a> - <a style='color: #FF0000; font-size:11px' href='http://www.mastop.com.br/produtos/publish/checkversion.php?lang=".$xoopsConfig['language']."&version=".round($xoopsModule->getVar('version') / 100, 2)."' target='_blank'>".MPU_ADM_CHKVERSION."</a></div>";
xoops_cp_footer();
?>

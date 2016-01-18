<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Arquivo para configuração do Módulo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================

// Dados do Módulo
$modversion['name'] = MPU_MOD_NOME;
$modversion['version'] = 1.1;
$modversion['author']  = 'Fernando Santos (aka topet05)';
$modversion['description'] = MPU_MOD_DESC;
$modversion['credits'] = "Mastop InfoDigital - www.mastop.com.br";
$modversion['help'] = "help.html";
$modversion['license'] = "GPL";
$modversion['official'] = 0;
$modversion['image'] = "images/mpub_mpublish_logo.gif";
$modversion['dirname'] = MPU_MOD_DIR;

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tabelas criadas pelo Arquivo sql (sem prefixo poha!)
$modversion['tables'][0] = MPU_MOD_TABELA1;
$modversion['tables'][1] = MPU_MOD_TABELA2;
$modversion['tables'][2] = MPU_MOD_TABELA3;
$modversion['tables'][3] = MPU_MOD_TABELA4;

// Itens da Administração
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Templates
$modversion['templates'][1]['file'] = MPU_MOD_TEMPLATE1;
$modversion['templates'][1]['description'] = MPU_MOD_TEMPLATE1_DESC;

// Blocos
$modversion['blocks'][1]['file'] = MPU_MOD_BLOCO1_FILE;
$modversion['blocks'][1]['name'] = MPU_MOD_BLOCO1;
$modversion['blocks'][1]['description'] = MPU_MOD_BLOCO1_DESC;
$modversion['blocks'][1]['show_func'] = MPU_MOD_BLOCO1_SHOW;
$modversion['blocks'][1]['edit_func'] = MPU_MOD_BLOCO1_EDIT;
$modversion['blocks'][1]['options'] = '0|1|Home|1|100%|170px|'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/menu/arrow.gif|FFFFFF|050200|333333|EBE8FA|1D5223|0|0|0|0|0|0|1px|solid|CCCCCC|1px|groove|000000|4px|0';
$modversion['blocks'][1]['template'] = MPU_MOD_BLOCO1_TEMPLATE; // Nome do Arquivo .html que ficará dentro da pasta templates/blocks (definir só se o bloco for utilizar templates).


$modversion['blocks'][2]['file'] = MPU_MOD_BLOCO2_FILE;
$modversion['blocks'][2]['name'] = MPU_MOD_BLOCO2;
$modversion['blocks'][2]['description'] = MPU_MOD_BLOCO2_DESC;
$modversion['blocks'][2]['show_func'] = MPU_MOD_BLOCO2_SHOW;
$modversion['blocks'][2]['edit_func'] = MPU_MOD_BLOCO2_EDIT;
$modversion['blocks'][2]['options'] = '15px|000000|/';

$modversion['blocks'][3]['file'] = MPU_MOD_BLOCO3_FILE;
$modversion['blocks'][3]['name'] = MPU_MOD_BLOCO3;
$modversion['blocks'][3]['description'] = MPU_MOD_BLOCO3_DESC;
$modversion['blocks'][3]['show_func'] = MPU_MOD_BLOCO3_SHOW;
$modversion['blocks'][3]['edit_func'] = MPU_MOD_BLOCO3_EDIT;
$modversion['blocks'][3]['options'] = '10px|2200FF|mpb_30_titulo|50';
$modversion['blocks'][3]['template'] = MPU_MOD_BLOCO3_TEMPLATE;

$modversion['blocks'][4]['file'] = MPU_MOD_BLOCO4_FILE;
$modversion['blocks'][4]['name'] = MPU_MOD_BLOCO4;
$modversion['blocks'][4]['description'] = MPU_MOD_BLOCO4_DESC;
$modversion['blocks'][4]['show_func'] = MPU_MOD_BLOCO4_SHOW;
$modversion['blocks'][4]['edit_func'] = MPU_MOD_BLOCO4_EDIT;
$modversion['blocks'][4]['options'] = 'mpub_menutree|1|Home|1|FFFFFF|F2F2F2|000000|757575|3C00AB|0|0|0|0|0|0|0';
$modversion['blocks'][4]['template'] = MPU_MOD_BLOCO4_TEMPLATE; // Nome do Arquivo .html que ficará dentro da pasta templates/blocks (definir só se o bloco for utilizar templates).

$modversion['blocks'][5]['file'] = MPU_MOD_BLOCO5_FILE;
$modversion['blocks'][5]['name'] = MPU_MOD_BLOCO5;
$modversion['blocks'][5]['description'] = MPU_MOD_BLOCO5_DESC;
$modversion['blocks'][5]['show_func'] = MPU_MOD_BLOCO5_SHOW;
$modversion['blocks'][5]['edit_func'] = MPU_MOD_BLOCO5_EDIT;
$modversion['blocks'][5]['options'] = '0|1|Home|1|150px|170px|'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/menu/plus.gif|FFFFFF|050200|333333|EBE8FA|1D5223|0|0|0|0|0|0|1px|solid|CCCCCC|1px|groove|000000|4px|0';
$modversion['blocks'][5]['template'] = MPU_MOD_BLOCO5_TEMPLATE; // Nome do Arquivo .html que ficará dentro da pasta templates/blocks (definir só se o bloco for utilizar templates).

$modversion['blocks'][6]['file'] = MPU_MOD_BLOCO6_FILE;
$modversion['blocks'][6]['name'] = MPU_MOD_BLOCO6;
$modversion['blocks'][6]['description'] = MPU_MOD_BLOCO6_DESC;
$modversion['blocks'][6]['show_func'] = MPU_MOD_BLOCO6_SHOW;
$modversion['blocks'][6]['edit_func'] = MPU_MOD_BLOCO6_EDIT;
$modversion['blocks'][6]['options'] = 'mpub_menurel|100%|170px|'.XOOPS_URL.'/modules/'.MPU_MOD_DIR.'/menu/arrow.gif|FFFFFF|050200|333333|EBE8FA|1D5223|0|0|0|0|0|0|1px|solid|CCCCCC|1px|groove|000000|4px';
$modversion['blocks'][6]['template'] = MPU_MOD_BLOCO6_TEMPLATE; // Nome do Arquivo .html que ficará dentro da pasta templates/blocks (definir só se o bloco for utilizar templates).


// Menu
$modversion['hasMain'] = 1;

// Busca
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/busca.inc.php";
$modversion['search']['func'] = MPU_MOD_BUSCA_FUNC;

// Comentários
$modversion['hasComments'] = 1;
$modversion['comments']['pageName'] = 'index.php';
$modversion['comments']['itemName'] = 'tac';
// Funções de retorno para comentários
//$modversion['comments']['callback']['approve'] = ''; // Nome da função que será executada toda vez que um comentário for aprovado. O único parâmetro passado é o objeto do Comentário que foi aprovado. Útil para notificar o usuário sobre a aprovação de seu comentário.
//$modversion['comments']['callback']['update'] = ''; // Nome da função que será executada toda vez que o número de comentários ativos for alterado. 2 Parâmetros: ID do item e Total de comentários ativos. Útil para atualizar o número de comentários de determinado registro.
//$modversion['comments']['callbackFile'] = ''; // Path (a partir da pasta do módulo) para o arquivo .php que contém as funções de retorno para os comentários.

// Configurações (Para as preferências do módulo)

$modversion['config'][1]['name'] = 'mpu_conf_home_id';
$modversion['config'][1]['title'] = 'MPU_MOD_HOME_ID';
$modversion['config'][1]['description'] = 'MPU_MOD_HOME_ID_DESC';
$modversion['config'][1]['formtype'] = 'texbox';
$modversion['config'][1]['valuetype'] = 'text';

$modversion['config'][2]['name'] = 'mpu_conf_wysiwyg'; // Nome do índice para reconhecer o valor específico da configuração. Ex.: Se for definido 'teste', será chamado como $xoopsModuleConfig['teste']
$modversion['config'][2]['title'] = 'MPU_MOD_WYSIWYG';
$modversion['config'][2]['description'] = 'MPU_MOD_WYSIWYG_DESC';
$modversion['config'][2]['formtype'] = 'yesno'; // Tipo de elemento usado no formulário de preferências. Pode ser 'yesno', 'select', 'select_multi', 'group', 'group_multi', 'textbox', 'textarea', 'user', 'user_multi', 'timezone', 'password', 'color', 'hidden' ou 'language'.
$modversion['config'][2]['valuetype'] = 'int'; // Valor esperado para o elemento no formulário. Pode ser 'int', 'float', 'text' ou 'array'. Para configurações que tenham o formtype como xx_multi, o valuetype é sempre array.
$modversion['config'][2]['default'] = 1; // Valor padrão da configuração. formtype como 'yesno' devem sempre receber 0 (zero) ou 1.
//$modversion['config'][2]['options'] = array(); // Opções para formtype tipo 'select' ou 'select_multi', deve ser um array (Ex.: array('5' => 5, '10' => 10, '15' => 15). Pode ser usado Constante de Tradução aqui.

$modversion['config'][3]['name'] = 'mpu_conf_wysiwyg_bkg';
$modversion['config'][3]['title'] = 'MPU_MOD_WYSIWYG_BKG';
$modversion['config'][3]['description'] = 'MPU_MOD_WYSIWYG_BKG_DESC';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 1;


$modversion['config'][4]['name'] = 'mpu_conf_wysiwyg_path';
$modversion['config'][4]['title'] = 'MPU_MOD_WYSIWYG_PATH';
$modversion['config'][4]['description'] = 'MPU_MOD_WYSIWYG_PATH_DESC';
$modversion['config'][4]['formtype'] = 'texbox';
$modversion['config'][4]['valuetype'] = 'text';
$modversion['config'][4]['default'] = '/modules/'.MPU_MOD_DIR.'/editor/tinymce/jscripts/tiny_mce';

$modversion['config'][5]['name'] = 'mpu_conf_wysiwyg_plugins';
$modversion['config'][5]['title'] = 'MPU_MOD_WYSIWYG_PLUGINS';
$modversion['config'][5]['description'] = 'MPU_MOD_WYSIWYG_PLUGINS_DESC';
$modversion['config'][5]['formtype'] = 'texbox';
$modversion['config'][5]['valuetype'] = 'text';
$modversion['config'][5]['default'] = 'inlinepopups,style,layer,table,save,advhr,advimage,advlink,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,youtube,keyword,xhtmlxtras';

$modversion['config'][6]['name'] = 'mpu_conf_wysiwyg_lang';
$modversion['config'][6]['title'] = 'MPU_MOD_WYSIWYG_LANG';
$modversion['config'][6]['description'] = 'MPU_MOD_WYSIWYG_LANG_DESC';
$modversion['config'][6]['formtype'] = 'texbox';
$modversion['config'][6]['valuetype'] = 'text';
$modversion['config'][6]['default'] = ($xoopsConfig['language'] == "portuguesebr") ? "pt_br" : "en";

$modversion['config'][7]['name'] = 'mpu_conf_wysiwyg_bt1b';
$modversion['config'][7]['title'] = 'MPU_MOD_WYSIWYG_BT1B';
$modversion['config'][7]['description'] = 'MPU_MOD_WYSIWYG_BT1B_DESC';
$modversion['config'][7]['formtype'] = 'texbox';
$modversion['config'][7]['valuetype'] = 'text';
$modversion['config'][7]['default'] = '';

$modversion['config'][8]['name'] = 'mpu_conf_wysiwyg_bt1';
$modversion['config'][8]['title'] = 'MPU_MOD_WYSIWYG_BT1';
$modversion['config'][8]['description'] = 'MPU_MOD_WYSIWYG_BT1_DESC';
$modversion['config'][8]['formtype'] = 'texbox';
$modversion['config'][8]['valuetype'] = 'text';
$modversion['config'][8]['default'] = 'fontselect,fontsizeselect';

$modversion['config'][9]['name'] = 'mpu_conf_wysiwyg_bt2b';
$modversion['config'][9]['title'] = 'MPU_MOD_WYSIWYG_BT2B';
$modversion['config'][9]['description'] = 'MPU_MOD_WYSIWYG_BT2B_DESC';
$modversion['config'][9]['formtype'] = 'texbox';
$modversion['config'][9]['valuetype'] = 'text';
$modversion['config'][9]['default'] = 'cut,copy,paste,pastetext,pasteword,separator,search,replace,separator';

$modversion['config'][10]['name'] = 'mpu_conf_wysiwyg_bt2';
$modversion['config'][10]['title'] = 'MPU_MOD_WYSIWYG_BT2';
$modversion['config'][10]['description'] = 'MPU_MOD_WYSIWYG_BT2_DESC';
$modversion['config'][10]['formtype'] = 'texbox';
$modversion['config'][10]['valuetype'] = 'text';
$modversion['config'][10]['default'] = 'separator,insertdate,inserttime,preview,separator,forecolor,backcolor,advsearchreplace';

$modversion['config'][11]['name'] = 'mpu_conf_wysiwyg_bt3b';
$modversion['config'][11]['title'] = 'MPU_MOD_WYSIWYG_BT3B';
$modversion['config'][11]['description'] = 'MPU_MOD_WYSIWYG_BT3B_DESC';
$modversion['config'][11]['formtype'] = 'texbox';
$modversion['config'][11]['valuetype'] = 'text';
$modversion['config'][11]['default'] = 'tablecontrols,separator';

$modversion['config'][12]['name'] = 'mpu_conf_wysiwyg_bt3';
$modversion['config'][12]['title'] = 'MPU_MOD_WYSIWYG_BT3';
$modversion['config'][12]['description'] = 'MPU_MOD_WYSIWYG_BT3_DESC';
$modversion['config'][12]['formtype'] = 'texbox';
$modversion['config'][12]['valuetype'] = 'text';
$modversion['config'][12]['default'] = 'advhr,separator,print,separator,ltr,rtl,separator,fullscreen';

$modversion['config'][13]['name'] = 'mpu_conf_wysiwyg_bt4';
$modversion['config'][13]['title'] = 'MPU_MOD_WYSIWYG_BT4';
$modversion['config'][13]['description'] = 'MPU_MOD_WYSIWYG_BT4_DESC';
$modversion['config'][13]['formtype'] = 'texbox';
$modversion['config'][13]['valuetype'] = 'text';
$modversion['config'][13]['default'] = 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,|,visualchars,nonbreaking,separator,keyword,media,youtube';

$modversion['config'][14]['name'] = 'mpu_conf_wysiwyg_frmtdata';
$modversion['config'][14]['title'] = 'MPU_MOD_WYSIWYG_FRMTDATA';
$modversion['config'][14]['description'] = 'MPU_MOD_WYSIWYG_FRMTDATA_DESC';
$modversion['config'][14]['formtype'] = 'texbox';
$modversion['config'][14]['valuetype'] = 'text';
$modversion['config'][14]['default'] = '%d/%m/%Y';

$modversion['config'][15]['name'] = 'mpu_conf_wysiwyg_frmthora';
$modversion['config'][15]['title'] = 'MPU_MOD_WYSIWYG_FRMTHORA';
$modversion['config'][15]['description'] = 'MPU_MOD_WYSIWYG_FRMTHORA_DESC';
$modversion['config'][15]['formtype'] = 'texbox';
$modversion['config'][15]['valuetype'] = 'text';
$modversion['config'][15]['default'] = '%H:%M:%S';

$modversion['config'][16]['name'] = 'mpu_conf_gzip';
$modversion['config'][16]['title'] = 'MPU_MOD_GZIP';
$modversion['config'][16]['description'] = 'MPU_MOD_GZIP_DESC';
$modversion['config'][16]['formtype'] = 'yesno';
$modversion['config'][16]['valuetype'] = 'int';
$modversion['config'][16]['default'] = 1;

$modversion['config'][17]['name'] = 'mpu_conf_mimetypes';
$modversion['config'][17]['title'] = 'MPU_MOD_MIMETYPES';
$modversion['config'][17]['description'] = 'MPU_MOD_MIMETYPES_DESC';
$modversion['config'][17]['formtype'] = 'select_multi';
$modversion['config'][17]['valuetype'] = 'array';
$modversion['config'][17]['options'] = include( XOOPS_ROOT_PATH . '/class/mimetypes.inc.php' );
ksort($modversion['config'][17]['options']);
reset($modversion['config'][17]['options']);
$modversion['config'][17]['default'] = array("application/x-gtar","application/x-tar","application/x-gzip","application/msword", "application/pdf", "application/vnd.ms-excel", "application/vnd.ms-excel", "application/vnd.ms-powerpoint", "application/zip");

$modversion['config'][18]['name'] = 'mpu_conf_contentmimes';
$modversion['config'][18]['title'] = 'MPU_MOD_CONTENTMIMES';
$modversion['config'][18]['description'] = 'MPU_MOD_CONTENTMIMES_DESC';
$modversion['config'][18]['formtype'] = 'select_multi';
$modversion['config'][18]['valuetype'] = 'array';
$modversion['config'][18]['options'] = array("html"=>"text/html", "txt"=>"text/plain", "php"=>"application/x-httpd-php", "js"=>"application/x-javascript");
$modversion['config'][18]['default'] = array("text/html", "text/plain", "application/x-httpd-php", "application/x-javascript");


$modversion['config'][19]['name'] = 'mpu_max_filesize';
$modversion['config'][19]['title'] = 'MPU_MOD_MAXFILESIZE';
$modversion['config'][19]['description'] = 'MPU_MOD_MAXFILESIZE_DESC';
$modversion['config'][19]['formtype'] = 'textbox';
$modversion['config'][19]['valuetype'] = 'int';
$modversion['config'][19]['default'] = intval(get_cfg_var('upload_max_filesize'))*1024;

$modversion['config'][20]['name'] = 'mpu_mmax_filesize';
$modversion['config'][20]['title'] = 'MPU_MOD_MMAXFILESIZE';
$modversion['config'][20]['description'] = 'MPU_MOD_MMAXFILESIZE_DESC';
$modversion['config'][20]['formtype'] = 'textbox';
$modversion['config'][20]['valuetype'] = 'int';
$modversion['config'][20]['default'] = intval(get_cfg_var('upload_max_filesize'))*1024;

$modversion['config'][21]['name'] = 'mpu_conf_nomes_id';
$modversion['config'][21]['title'] = 'MPU_MOD_NOMES_ID';
$modversion['config'][21]['description'] = 'MPU_MOD_NOMES_ID_DESC';
$modversion['config'][21]['formtype'] = 'yesno';
$modversion['config'][21]['valuetype'] = 'int';
$modversion['config'][21]['default'] = 1;

$modversion['config'][22]['name'] = 'mpu_conf_iframe_width';
$modversion['config'][22]['title'] = 'MPU_MOD_IFRAME_WIDTH';
$modversion['config'][22]['description'] = 'MPU_MOD_IFRAME_WIDTH_DESC';
$modversion['config'][22]['formtype'] = 'texbox';
$modversion['config'][22]['valuetype'] = 'text';
$modversion['config'][22]['default'] = '100%';

$modversion['config'][23]['name'] = 'mpu_conf_iframe_height';
$modversion['config'][23]['title'] = 'MPU_MOD_IFRAME_HEIGHT';
$modversion['config'][23]['description'] = 'MPU_MOD_IFRAME_HEIGHT_DESC';
$modversion['config'][23]['formtype'] = 'texbox';
$modversion['config'][23]['valuetype'] = 'text';
$modversion['config'][23]['default'] = '500';

$modversion['config'][24]['name'] = 'mpu_conf_navigation';
$modversion['config'][24]['title'] = 'MPU_MOD_NAVIGATION';
$modversion['config'][24]['description'] = 'MPU_MOD_NAVIGATION_DESC';
$modversion['config'][24]['formtype'] = 'yesno';
$modversion['config'][24]['valuetype'] = 'int';
$modversion['config'][24]['default'] = 1;

$modversion['config'][25]['name'] = 'mpu_conf_related';
$modversion['config'][25]['title'] = 'MPU_MOD_RELATED';
$modversion['config'][25]['description'] = 'MPU_MOD_RELATED_DESC';
$modversion['config'][25]['formtype'] = 'yesno';
$modversion['config'][25]['valuetype'] = 'int';
$modversion['config'][25]['default'] = 1;

$modversion['config'][26]['name'] = 'mpu_conf_captcha';
$modversion['config'][26]['title'] = 'MPU_MOD_CAPTCHA';
$modversion['config'][26]['description'] = 'MPU_MOD_CAPTCHA_DESC';
$modversion['config'][26]['formtype'] = 'yesno';
$modversion['config'][26]['valuetype'] = 'int';
$modversion['config'][26]['default'] = (function_exists('imagecreate')) ? 1 : 0;

$modversion['config'][27]['name'] = 'mpu_conf_canedit';
$modversion['config'][27]['title'] = 'MPU_MOD_CANEDIT';
$modversion['config'][27]['description'] = 'MPU_MOD_CANEDIT_DESC';
$modversion['config'][27]['formtype'] = 'yesno';
$modversion['config'][27]['valuetype'] = 'int';
$modversion['config'][27]['default'] = 0;

$modversion['config'][28]['name'] = 'mpu_conf_cancreate';
$modversion['config'][28]['title'] = 'MPU_MOD_CANCREATE';
$modversion['config'][28]['description'] = 'MPU_MOD_CANCREATE_DESC';
$modversion['config'][28]['formtype'] = 'yesno';
$modversion['config'][28]['valuetype'] = 'int';
$modversion['config'][28]['default'] = 0;

$modversion['config'][29]['name'] = 'mpu_conf_candelete';
$modversion['config'][29]['title'] = 'MPU_MOD_CANDELETE';
$modversion['config'][29]['description'] = 'MPU_MOD_CANDELETE_DESC';
$modversion['config'][29]['formtype'] = 'yesno';
$modversion['config'][29]['valuetype'] = 'int';
$modversion['config'][29]['default'] = 0;
?>
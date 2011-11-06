<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Classe para manipulação de Conteúdo
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_geral.class.php";
class mpu_mpb_mpublish extends mpu_geral {
    function mpu_mpb_mpublish($id=null){
        $this->db =& Database::getInstance();
        $this->tabela = $this->db->prefix(MPU_MOD_TABELA1);
        $this->id = "mpb_10_id";
        $this->initVar("mpb_10_id", XOBJ_DTYPE_INT, null, false);
        $this->initVar("mpb_10_idpai", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("usr_10_uid", XOBJ_DTYPE_INT, null, false);
        $this->initVar("mpb_30_menu", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("mpb_30_titulo", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("mpb_35_conteudo", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("mpb_12_semlink", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_30_arquivo", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("mpb_11_visivel", XOBJ_DTYPE_INT, 2, false);
        $this->initVar("mpb_11_abrir", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_12_comentarios", XOBJ_DTYPE_INT, null, false);
        $this->initVar("mpb_12_exibesub", XOBJ_DTYPE_INT, 1, false);
        $this->initVar("mpb_12_recomendar", XOBJ_DTYPE_INT, 1, false);
        $this->initVar("mpb_12_imprimir", XOBJ_DTYPE_INT, 1, false);
        $this->initVar("mpb_22_criado", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_22_atualizado", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_10_ordem", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("mpb_10_contador", XOBJ_DTYPE_INT, 0, false);
        if ( !empty($id) ) {
            if ( is_array($id) ) {
                $this->assignVars($id);
            }elseif (is_numeric($id)){
                $this->load(intval($id));
            }else{
                $this->loadByMenu($id);
            }
        }

    }
    function loadByMenu($menu)
    {
        $myts =& MyTextSanitizer::getInstance();
        $sql = "SELECT * FROM ".$this->tabela." WHERE mpb_30_menu='".$myts->addSlashes(html_entity_decode(urldecode($menu), ENT_QUOTES))."'";
        $myrow = $this->db->fetchArray($this->db->query($sql));
        if (is_array($myrow) && count($myrow) > 0) {
            $this->assignVars($myrow);
            return true;
        }else{
            return false;
        }
    }
    function cat_principal($mpb_10_id) {
        $cat_principal_query = $this->db->query("select count(*) as count from " . $this->tabela . " where mpb_10_id = '" . (int)$mpb_10_id . "' and mpb_10_idpai=0");
        $cat_principal = $this->db->fetchArray($cat_principal_query);
        if ($cat_principal['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    function tem_subcategorias($mpb_10_id=null, $menu = false) {
        global $xoopsUser;
        $id = (empty($mpb_10_id)) ? $this->getVar($this->id) : $mpb_10_id;
        if(!$menu){
            $cat_filha_query = $this->db->query("select count(*) as count from " . $this->tabela . " where mpb_10_idpai =" . $id);
            $cat_filha = $this->db->fetchArray($cat_filha_query);
            if ($cat_filha['count'] > 0) {
                return true;
            } else {
                return false;
            }
        }else{
            $pages = $this->getPages();
            $cat_filha_query = $this->db->query("SELECT ".$this->id." FROM " . $this->tabela . " where mpb_10_idpai =" . $id . " AND mpb_11_visivel < 3");
            $total =  $this->db->getRowsNum($cat_filha_query);
            if ($total > 0){
                while ( $myrow = $this->db->fetchArray($cat_filha_query) ) {
                    if (in_array($myrow[$this->id], $pages)) {
                        return true;
                    }
                }
            }
            return false;
        }
    }
    function geraMenuSelect($mpb_10_idpai=0, $modules = true) {
        global $xoopsUser;
        $retorna[0] = MPU_ADM_MENUP;
        if ($mpb_10_idpai == 0 && $modules == true){
            $module_handler =& xoops_gethandler('module');
            $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
            $criteria->add(new Criteria('isactive', 1));
            $criteria->add(new Criteria('weight', 0, '>'));
            $modules = $module_handler->getObjects($criteria, true);
            $moduleperm_handler =& xoops_gethandler('groupperm');
            $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
            $read_allowed = $moduleperm_handler->getItemIds('module_read', $groups);
            foreach (array_keys($modules) as $i) {
                if (in_array($i, $read_allowed)) {
                    $subpgs = $this->tem_subcategorias($modules[$i]->getVar("mid")*-1);
                    $retorna[($modules[$i]->getVar("mid"))*-1] = "-".$modules[$i]->getVar('name');
                    if ($subpgs) {
                        $retornaS = $this->geraMenuSelect(($modules[$i]->getVar("mid"))*-1);
                        foreach ($retornaS as $k => $v) {
                            $retorna[$k] = "&nbsp;&nbsp;&nbsp;  ".$v;
                        }
                    }
                }
            }
        }
        $tem_subs = 0;
        $cat_principal = 0;
        $categorias_query_catmenu = $this->db->query("select mpb_10_id, mpb_10_idpai, mpb_30_menu from ".$this->tabela." where mpb_10_idpai=" . (int)$mpb_10_idpai . " order by mpb_10_ordem, mpb_30_menu");
        while ($categorias = $this->db->fetchArray($categorias_query_catmenu))  {
            if($this->getVar($this->id) > 0 && $this->getVar($this->id) == $categorias['mpb_10_id']){
                continue;
            }
            $tem_subs= $this->tem_subcategorias($categorias['mpb_10_id']);
            $cat_principal = $this->cat_principal($categorias['mpb_10_id']);
            if ($cat_principal) {
                $categorias['mpb_30_menu'] = "-".$categorias['mpb_30_menu'];
            }
            if ($tem_subs) {
                $retorna[$categorias['mpb_10_id']] = $categorias['mpb_30_menu'];
                $retorna2 = $this->geraMenuSelect($categorias['mpb_10_id']);
                foreach ($retorna2 as $k => $v) {
                    $retorna[$k] = "&nbsp;&nbsp;&nbsp;  ".$v;
                }
            } else {
                $retorna[$categorias['mpb_10_id']] = $categorias['mpb_30_menu'];
            }
        }
        return $retorna;
    }

    function getPages(){
        global $xoopsUser;
        static $myPages = array();
        $module_handler =& xoops_gethandler('module');
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $moduleperm_handler =& xoops_gethandler('groupperm');
        $mpu_module =& $module_handler->getByDirname(MPU_MOD_DIR);
        if (empty($myPages)) {
        $myPages = $moduleperm_handler->getItemIds('mpu_mpublish_acesso', $groups, $mpu_module->getVar('mid'));
        }
        return $myPages;
    }

    function geraMenuCSS($criterio = null, $modulos = true) {
        global $xoopsUser;
        if (!is_object($criterio)) {
            $criterio = new CriteriaCompo(new Criteria("mpb_10_idpai", 0));
            $first = true;
            $ret = array();
        }else{
            $first = false;
            $ret = "";
        }
        $criterio->setSort("mpb_10_ordem");
        $criterio->add(new Criteria("mpb_11_visivel", 3, "<"));
        $module_handler =& xoops_gethandler('module');
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $moduleperm_handler =& xoops_gethandler('groupperm');
        $mpu_module =& $module_handler->getByDirname(MPU_MOD_DIR);
        //Início Rotina Módulos
        if ($modulos){
            $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
            $criteria->add(new Criteria('isactive', 1));
            $criteria->add(new Criteria('weight', 0, '>'));
            $modules = $module_handler->getObjects($criteria, true);
            $read_allowed = $moduleperm_handler->getItemIds('module_read', $groups);
            foreach (array_keys($modules) as $i) {
                if (in_array($i, $read_allowed)) {
                    $sublinks = $modules[$i]->subLink();
                    $subpgs = $this->tem_subcategorias($modules[$i]->getVar("mid")*-1, true);
                    $ret[$modules[$i]->getVar('weight')][($modules[$i]->getVar("mid"))*-1] = "<li><a href='".XOOPS_URL."/modules/". $modules[$i]->getVar('dirname') ."/"."'>".$modules[$i]->getVar('name')."</a>";
                    if ((count($sublinks) > 0) || $subpgs) {
                        $ret[$modules[$i]->getVar('weight')][($modules[$i]->getVar("mid"))*-1] .= "<ul>\n";
                        if (count($sublinks) > 0) {
                            foreach($sublinks as $sublink){
                                $ret[$modules[$i]->getVar('weight')][($modules[$i]->getVar("mid"))*-1] .="<li><a href='".XOOPS_URL."/modules/". $modules[$i]->getVar('dirname') ."/".$sublink['url'] ."'>".$sublink['name']."</a></li>\n";
                            }
                        }
                        if ($subpgs) {
                            $ret[$modules[$i]->getVar('weight')][($modules[$i]->getVar("mid"))*-1] .= $this->geraMenuCSS(new CriteriaCompo(new Criteria("mpb_10_idpai", ($modules[$i]->getVar("mid"))*-1)), false);
                        }
                        $ret[$modules[$i]->getVar('weight')][($modules[$i]->getVar("mid"))*-1] .="\n</ul>";
                    }
                    $ret[$modules[$i]->getVar('weight')][($modules[$i]->getVar("mid"))*-1] .= "</li>\n";
                }
            }
        }

        //Fim Rotina Módulos
        $itens = $this->PegaTudo($criterio);
        if ($itens) {
            $pages = $this->getPages();
            foreach ($itens as $it) {
                if (!in_array($it->getVar($it->id), $pages)) {
                    continue;
                }
                if($first){
                    $ret[$it->getVar("mpb_10_ordem")][$it->getVar($it->id)] = "<li><a href='".$it->pegaLink()."' ".(($it->getVar("mpb_11_abrir") == 1) ? "target='_blank'" : "").">".$it->getVar("mpb_30_menu")."</a>";
                    $subpgs = ($it->getVar("mpb_12_exibesub")) ? $it->tem_subcategorias($it->getVar($it->id), true) : 0;
                    $ret[$it->getVar("mpb_10_ordem")][$it->getVar($it->id)] .= ($subpgs) ? "<ul>\n".$it->geraMenuCSS(new CriteriaCompo(new Criteria("mpb_10_idpai", $it->getVar($it->id))), false)."\n</ul>\n</li>\n" : "</li>\n";
                }else{
                    $ret .= "<li><a href='".$it->pegaLink()."' ".(($it->getVar("mpb_11_abrir") == 1) ? "target='_blank'" : "").">".$it->getVar("mpb_30_menu")."</a>";
                    $subpgs = ($it->getVar("mpb_12_exibesub")) ? $it->tem_subcategorias($it->getVar($it->id), true) : 0;
                    $ret .= ($subpgs) ? "<ul>\n".$it->geraMenuCSS(new CriteriaCompo(new Criteria("mpb_10_idpai", $it->getVar($it->id))), false)."\n</ul>\n</li>\n" : "</li>\n";
                }
            }
        }

        if($first){
            $retorno = "";
            ksort($ret);
            foreach ($ret as $k=>$v) {
                foreach ($v as $b) {
                    $retorno .= $b;
                }
            }
            return $retorno;
        }
        return $ret;
    }
    function geraMenuRelated($criterio) {
        global $xoopsUser;
        if (!is_object($criterio)) {
            $criterio = new CriteriaCompo(new Criteria("mpb_10_idpai", $this->getVar('mpb_10_idpai')));
            $first = true;
            $ret = array();
        }else{
            $first = false;
            $ret = "";
        }
        $criterio->setSort("mpb_10_ordem");
        $criterio->add(new Criteria("mpb_11_visivel", 3, "<"));
        $module_handler =& xoops_gethandler('module');
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $moduleperm_handler =& xoops_gethandler('groupperm');
        $mpu_module =& $module_handler->getByDirname(MPU_MOD_DIR);
        $itens = $this->PegaTudo($criterio);
        if ($itens) {
            $pages = $this->getPages();
            foreach ($itens as $it) {
                if (!in_array($it->getVar($it->id), $pages)) {
                    continue;
                }
                if($first){
                    $ret[$it->getVar("mpb_10_ordem")][$it->getVar($it->id)] = "<li><a href='".$it->pegaLink()."' ".(($it->getVar("mpb_11_abrir") == 1) ? "target='_blank'" : "").">".$it->getVar("mpb_30_menu")."</a>";
                    $subpgs = ($it->getVar("mpb_12_exibesub")) ? $it->tem_subcategorias($it->getVar($it->id), true) : 0;
                    $ret[$it->getVar("mpb_10_ordem")][$it->getVar($it->id)] .= ($subpgs) ? "<ul>\n".$it->geraMenuRelated(new CriteriaCompo(new Criteria("mpb_10_idpai", $it->getVar($it->id))))."\n</ul>\n</li>\n" : "</li>\n";
                }else{
                    $ret .= "<li><a href='".$it->pegaLink()."' ".(($it->getVar("mpb_11_abrir") == 1) ? "target='_blank'" : "").">".$it->getVar("mpb_30_menu")."</a>";
                    $subpgs = ($it->getVar("mpb_12_exibesub")) ? $it->tem_subcategorias($it->getVar($it->id), true) : 0;
                    $ret .= ($subpgs) ? "<ul>\n".$it->geraMenuRelated(new CriteriaCompo(new Criteria("mpb_10_idpai", $it->getVar($it->id))))."\n</ul>\n</li>\n" : "</li>\n";
                }
            }
        }

        if($first){
            $retorno = "";
            ksort($ret);
            foreach ($ret as $k=>$v) {
                foreach ($v as $b) {
                    $retorno .= $b;
                }
            }
            return $retorno;
        }
        return $ret;
    }

    function pegaLink($pg = null){
        if ($this->getVar('mpb_12_semlink')) {
            return 'javascript:void(0);';
        }elseif (substr($this->getVar("mpb_30_arquivo"), 0, 4) == "ext:"){
            return substr($this->getVar("mpb_30_arquivo"), 4);
        }
        $module_handler =& xoops_gethandler('module');
        $module =& $module_handler->getByDirname(MPU_MOD_DIR);
        $config_handler =& xoops_gethandler('config');
        $MeuModCFG =& $config_handler->getConfigsByCat(0,$module->getVar('mid'));
        if(!empty($MeuModCFG['mpu_conf_nomes_id'])){
            if ($this->contar(new Criteria("mpb_30_menu", $this->getVar("mpb_30_menu"))) > 1) {
                return XOOPS_URL."/modules/".MPU_MOD_DIR."/$pg?tac=".$this->getVar($this->id);
            }else{
                return XOOPS_URL."/modules/".MPU_MOD_DIR."/$pg?tac=".urlencode(str_replace(" ", "_", $this->getVar("mpb_30_menu")));
            }
        }else{
            return XOOPS_URL."/modules/".MPU_MOD_DIR."/$pg?tac=".$this->getVar($this->id);
        }
    }

    function updateCount(){
        $sql = 'UPDATE '.$this->tabela.' SET mpb_10_contador=mpb_10_contador+1 WHERE '.$this->id.'='.$this->getVar($this->id);
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        return true;
    }
    function geraNavigation($id = null, $separador = "/", $style="style='font-weight:bold'"){
        if($id == null){
            $ret = "<span $style>".$this->getVar("mpb_30_menu")."</span>";
            if ($this->getVar("mpb_10_idpai") == 0) {
                $ret = "<a href='".XOOPS_URL."'>Home</a> $separador ".$ret;
                return $ret;
            }elseif ($this->getVar("mpb_10_idpai") > 0){
                $ret = $this->geraNavigation($this->getVar("mpb_10_idpai"), $separador)." $separador ". $ret;
            }else{
                $module_handler =& xoops_gethandler('module');
                $module = $module_handler->get($this->getVar("mpb_10_idpai")*-1);
                $ret = "<a href='".XOOPS_URL."'>Home</a> $separador ".$module->mainLink()." $separador ".$ret;
                return $ret;
            }
        }else{
            if ($id > 0) {
                $classe = get_class($this);
                $novo = new $classe($id);
                if ($novo->getVar($novo->id) > 0) {
                    $ret = "<a href='".$novo->pegaLink()."' ".(($novo->getVar("mpb_11_abrir") == 1) ? "target='_blank'" : "").">".$novo->getVar("mpb_30_menu")."</a>";
                    if ($novo->getVar("mpb_10_idpai") == 0) {
                        return "<a href='".XOOPS_URL."'>Home</a> $separador ".$ret;
                    }elseif ($novo->getVar("mpb_10_idpai") > 0){
                        $ret = $novo->geraNavigation($novo->getVar("mpb_10_idpai"), $separador)." $separador ". $ret;
                    }else{
                        $module_handler =& xoops_gethandler('module');
                        $module = $module_handler->get($novo->getVar("mpb_10_idpai")*-1);
                        $ret = "<a href='".XOOPS_URL."'>Home</a> $separador ".$module->mainLink()." $separador ".$ret;
                        return $ret;
                    }
                }
            }
        }
        return $ret;
    }

    function geraNavigationAdmin($id = null, $separador = "/", $style="style='font-weight:bold'"){
        $admin_url = XOOPS_URL."/modules/".MPU_MOD_DIR."/admin/index.php?op=listar";
        if ($id == false) {
            return false;
        }elseif($id == null){
            $ret = "<span $style>".$this->getVar("mpb_30_menu")."</span>";
            if ($this->getVar("mpb_10_idpai") == 0) {
                $ret = "<a href='".$admin_url."&mpb_10_id=0'>".MPU_ADM_MENUP."</a> $separador ".$ret;
                return $ret;
            }elseif ($this->getVar("mpb_10_idpai") > 0){
                $ret = $this->geraNavigationAdmin($this->getVar("mpb_10_idpai"), $separador)." $separador ". $ret;
            }else{
                $module_handler =& xoops_gethandler('module');
                $module = $module_handler->get($this->getVar("mpb_10_idpai")*-1);
                $ret = "<a href='".$admin_url."&mpb_10_id=0'>".MPU_ADM_MENUP."</a> $separador <a href='".$admin_url."&mpb_10_id=".$this->getVar("mpb_10_idpai")."'>".$module->getVar('name')."</a> $separador ".$ret;
                return $ret;
            }
        }elseif ($id < 0){
            $module_handler =& xoops_gethandler('module');
            $module = $module_handler->get($id*-1);
            $ret = "<a href='".$admin_url."&mpb_10_id=0'>".MPU_ADM_MENUP."</a> $separador <a href='".$admin_url."&mpb_10_id=".$id."'>".$module->getVar('name')."</a>";
            return $ret;
        }else{
            if ($id > 0) {
                $classe = get_class($this);
                $novo = new $classe($id);
                if ($novo->getVar($novo->id) > 0) {
                    $ret = "<a href='".$admin_url."&mpb_10_id=".$novo->getVar("mpb_10_id")."'>".$novo->getVar("mpb_30_menu")."</a>";
                    if ($novo->getVar("mpb_10_idpai") == 0) {
                        return "<a href='".$admin_url."&mpb_10_id=0'>".MPU_ADM_MENUP."</a> $separador ".$ret;
                    }elseif ($novo->getVar("mpb_10_idpai") > 0){
                        $ret = $novo->geraNavigationAdmin($novo->getVar("mpb_10_idpai"), $separador)." $separador ". $ret;
                    }else{
                        $module_handler =& xoops_gethandler('module');
                        $module = $module_handler->get($novo->getVar("mpb_10_idpai")*-1);
                        $ret = "<a href='".$admin_url."&mpb_10_id=0'>".MPU_ADM_MENUP."</a> $separador <a href='".$admin_url."&mpb_10_id=".$novo->getVar("mpb_10_idpai")."'>".$module->getVar('name')."</a> $separador ".$ret;
                        return $ret;
                    }
                }
            }else{
                return false;
            }
        }
        return $ret;
    }
    function PegaSmileys($campo = 'mpb_35_conteudo')
    {

        $myts =& MyTextSanitizer::getInstance();
        $smiles =& $myts->getSmileys();
        $ret = '';
        if (empty($smileys)) {
            $db =& Database::getInstance();
            if ($result = $db->query('SELECT * FROM '.$db->prefix('smiles').' WHERE display=1')) {
                while ($smiles = $db->fetchArray($result)) {
                    $ret .= "<img onclick=\"tinyMCE.execInstanceCommand('$campo', 'mceInsertContent', false, '<img src=\'".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles['smile_url'], ENT_QUOTES)."\' />');\" onmouseover='style.cursor=\"hand\"' src='".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles['smile_url'], ENT_QUOTES)."' alt='".$smiles['emotion']."' />";
                }
            }
        } else {
            $count = count($smiles);
            for ($i = 0; $i < $count; $i++) {
                if ($smiles[$i]['display'] == 1) {
                    $ret .= "<img onclick=\"tinyMCE.execInstanceCommand('$campo', 'mceInsertContent', false, '<img src=\'".XOOPS_UPLOAD_URL."/".htmlspecialchars($smiles[$i]['smile_url'], ENT_QUOTES)."\' />');\" onmouseover='style.cursor=\"hand\"' src='".XOOPS_UPLOAD_URL."/".$myts->oopsHtmlSpecialChars($smiles[$i]['smile_url'])."' border='0' alt='".$smiles[$i]['emotion']."' />";
                }
            }
        }
        return $ret;
    }

    function countSubs($mpb_10_id=null, $menu = false) {
        global $xoopsUser;
        $id = (empty($mpb_10_id)) ? $this->getVar($this->id) : $mpb_10_id;
        if(!$menu){
            $subs_query = $this->db->query("select count(*) as count from " . $this->tabela . " where mpb_10_idpai =" . $id);
            $cat_subs = $this->db->fetchArray($subs_query);
            if ($cat_subs['count'] > 0) {
                return $cat_subs['count'];
            } else {
                return 0;
            }
        }else{
            $pages = $this->getPages();
            $subs_query = $this->db->query("SELECT ".$this->id." FROM " . $this->tabela . " where mpb_10_idpai =" . $id . " AND mpb_11_visivel < 3");
            $total =  $this->db->getRowsNum($subs_query);
            if ($total > 0){
                $mySubs = 0;
                while ( $myrow = $this->db->fetchArray($cat_filha_query) ) {
                    if (in_array($myrow[$this->id], $pages)) {
                        $mySubs++;
                    }
                }
                return $mySubs;
            }else{
                return false;
            }
        }
    }
}
?>
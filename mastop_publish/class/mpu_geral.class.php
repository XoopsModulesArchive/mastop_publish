<?php
### =============================================================
### Mastop InfoDigital - Paixão por Internet
### =============================================================
### Classe MÃE
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital © 2003-2007
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";
if (!class_exists('mpu_geral')) {
    class mpu_geral extends XoopsObject
    {
        var $db;
        var $tabela;
        var $id;
        var $total=0;
        var $afetadas=0;
        // construtor da classe
        function mpu_geral()
        {
            // Não usado diretamente
        }

        function store()
        {
            if ( !$this->cleanVars() ) {
                return false;
            }
            $myts =& MyTextSanitizer::getInstance();
            foreach ( $this->cleanVars as $k=>$v ) {
                $indices[] = $k;
                $valores[] = $v;
                //$$k = $v;
            }
            if (is_null($this->getVar($this->id)) || $this->getVar($this->id) == 0) {
                $sql = "INSERT INTO ".$this->tabela." (";
                $sql .= implode(",", $indices);
                $sql .= ") VALUES (";
                for ($i = 0; $i<count($valores); $i++){
                    if(!is_int($valores[$i])){
                        $sql .= $this->db->quoteString($valores[$i]);
                    }else{
                        $sql .= $valores[$i];
                    }
                    if ($i != (count($valores)-1)) {
                        $sql .= ",";
                    }
                }
                $sql .= ")";
            }else {
                $sql ="UPDATE ".$this->tabela." SET ";
                for ($i = 1; $i<count($valores); $i++){
                    $sql .= $indices[$i]."=";
                    if(!is_int($valores[$i])){
                        $sql .= $this->db->quoteString($valores[$i]);
                    }else{
                        $sql .= $valores[$i];
                    }
                    if ($i != (count($valores)-1)) {
                        $sql .= ",";
                    }
                }
                $sql .= " WHERE ".$this->id." = ".$this->getVar($this->id);
            }
            //echo $sql;
            $result = $this->db->query($sql);
            $this->afetadas = $this->db->getAffectedRows();
            if (!$result) {
                $this->setErrors("Erro ao gravar dados na Base de Dados. <br />".$this->db->error());
                return false;
            }
            if (is_null($this->getVar($this->id)) || $this->getVar($this->id) == 0) {
                $this->setVar($this->id, $this->db->getInsertId());
                return $this->db->getInsertId();
            }
            return $this->getVar($this->id);
        }

        function atualizaTodos($campo, $valor, $criterio = null)
        {
            $set_clause = is_numeric($valor) ? $campo.' = '.$valor : $campo.' = '.$this->db->quoteString($valor);
            $sql = 'UPDATE '.$this->tabela.' SET '.$set_clause;
            if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
                $sql .= ' '.$criterio->renderWhere();
            }
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            return true;
        }

        function delete()
        {
            $sql = sprintf("DELETE FROM %s WHERE ".$this->id." = %u", $this->tabela, $this->getVar($this->id));
            if ( !$this->db->query($sql) ) {
                return false;
            }
            $this->afetadas = $this->db->getAffectedRows();
            return true;
        }

        function deletaTodos($criterio = null)
        {
            $sql = 'DELETE FROM '.$this->tabela;
            if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
                $sql .= ' '.$criterio->renderWhere();
            }
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            $this->afetadas = $this->db->getAffectedRows();
            return true;
        }

        function load($id)
        {
            $sql = "SELECT * FROM ".$this->tabela." WHERE ".$this->id."=".$id." LIMIT 1";
            $myrow = $this->db->fetchArray($this->db->query($sql));
            if (is_array($myrow) && count($myrow) > 0) {
                $this->assignVars($myrow);
                return true;
            }else{
                return false;
            }
        }

        function loadLast()
        {
            $sql = "SELECT * FROM ".$this->tabela." ORDER BY ".$this->id." DESC LIMIT 1";
            $myrow = $this->db->fetchArray($this->db->query($sql));
            if (is_array($myrow) && count($myrow) > 0) {
                $this->assignVars($myrow);
                return true;
            }else{
                return false;
            }
        }

        function PegaTudo($criterio=null, $objeto=true, $join = null)
        {
            $ret = array();
            $limit = $start = 0;
            $classe = get_class($this);
            if ( !$objeto ) {
                $sql = 'SELECT '.$this->id.' FROM '.$this->tabela;
                if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
                    $sql .= ' '.$criterio->renderWhere();
                    if ($criterio->getSort() != '') {
                        $sql .= ' ORDER BY '.$criterio->getSort().' '.$criterio->getOrder();
                    }
                    $limit = $criterio->getLimit();
                    $start = $criterio->getStart();
                }
                $result = $this->db->query($sql, $limit, $start);
                $this->total = $this->db->getRowsNum($result);
                if ($this->total > 0){
                    while ( $myrow = $this->db->fetchArray($result) ) {
                        $ret[] = $myrow[$this->id];
                    }
                    return $ret;
                }else{
                    return false;
                }
            } else {
                $sql = 'SELECT '.$this->tabela.'.* FROM '.$this->tabela.((!empty($join))? " ".$join : "");
                if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
                    $sql .= ' '.$criterio->renderWhere();
                    if ($criterio->getSort() != '') {
                        $sql .= ' ORDER BY '.$criterio->getSort().' '.$criterio->getOrder();
                    }
                    $limit = $criterio->getLimit();
                    $start = $criterio->getStart();
                }
                $result = $this->db->query($sql, $limit, $start);
                $this->total = $this->db->getRowsNum($result);
                if ($this->total > 0){
                    while ( $myrow = $this->db->fetchArray($result) ) {
                        $ret[] = new $classe($myrow);
                    }
                    return $ret;
                }else{
                    return false;
                }
            }
        }
        function administracao($url, $campos) {
            $criterio = new CriteriaCompo();
            if(!empty($campos['precrit']['campo']) && !empty($campos['precrit']['valor'])){
                $precrit_hidden = "";
                $precrit_url = "";
                foreach ($campos['precrit']['campo'] as $k=>$v) {
                    $operador = (isset($campos['precrit']['operador'][$k])) ? $campos['precrit']['operador'][$k] : "=";
                    $hiddens[$v] = $campos['precrit']['valor'][$k];
                    $criterio->add(new Criteria($v, $campos['precrit']['valor'][$k], $operador, $this->tabela));
                    $precrit_hidden .= "<input type='hidden' name='".$v."' value='".$campos['precrit']['valor'][$k]."'>";
                    $precrit_url .= "&".$v."=".$campos['precrit']['valor'][$k];
                }
            }else{
                $precrit_hidden = "";
                $precrit_url = "";
            }
            if(!empty($campos['checks']) && !empty($_POST['group_action']) && is_array($_POST['checks']) && $_POST['group_action'] == "group_del_ok"){
                $chks = $_POST['checks'];
                $classe = get_class($this);
                foreach ($chks as $k=>$v) {
                    $nova = new $classe($k);
                    if (!empty($campos['group_del_function']) && is_array($campos['group_del_function'])) {
                        foreach ($campos['group_del_function'] as $k=>$v)
                        $nova->$v();
                    }
                    $nova->delete();
                }
            }
            if (!empty($campos['checks']) && !empty($_POST['group_action']) && $_POST['group_action'] == "group_del" && is_array($_POST['checks'])) {
                $chks = $_POST['checks'];
                foreach ($chks as $k=>$v) {
                    $hiddens['checks['.$k.']'] = 1;
                }
                $hiddens['op'] = $campos['op'];
                $hiddens['group_action'] = 'group_del_ok';
                return xoops_confirm($hiddens, $url, $campos['lang']['group_del_sure'], _SUBMIT)."<br />";
            }
            $busca_url = '';
            if (!empty($_GET['busca'])) {
                foreach ($_GET['busca'] as $k => $v) {
                    if($v != '' && $v != '-1' && in_array($k, $campos['nome'])){
                        if(is_numeric($v)){
                            $criterio->add(new Criteria($k, $v, "=", $this->tabela));
                        }elseif (is_array($v)){
                            if (!empty($v['dday']) || !empty($v['dmonth']) || !empty($v['dyear']) || !empty($v['aday']) || !empty($v['amonth']) || !empty($v['ayear'])) {
                                $dday = (!empty($v['dday'])) ? $v['dday'] : 1;
                                $dmonth = (!empty($v['dmonth'])) ? $v['dmonth'] : 1;
                                $dyear = (!empty($v['dyear'])) ? $v['dyear'] : 1;
                                $aday = (!empty($v['aday'])) ? $v['aday'] : 1;
                                $amonth = (!empty($v['amonth'])) ? $v['dmonth'] : 1;
                                $ayear = (!empty($v['ayear'])) ? $v['ayear'] : date("Y");
                                $ddate = mktime(0,0,0,$v['dmonth'], $v['dday'], $v['dyear']);
                                $adate = mktime(0,0,0,$v['amonth'], $v['aday'], $v['ayear']);
                                $criterio->add(new Criteria($k, $ddate, ">=", $this->tabela));
                                $criterio->add(new Criteria($k, $adate, "<=", $this->tabela));
                            }
                        }else{
                            $criterio->add(new Criteria($k, "%$v%",'LIKE', $this->tabela));
                        }
                        $busca_url .= (!is_array($v)) ? '&busca['.$k.']='.$v : '&busca['.$k.'][dday]='.$v['dday'].'&busca['.$k.'][dmonth]='.$v['dmonth'].'&busca['.$k.'][dyear]='.$v['dyear'].'&busca['.$k.'][aday]='.$v['aday'].'&busca['.$k.'][amonth]='.$v['amonth'].'&busca['.$k.'][ayear]='.$v['ayear'];
                    }
                }
            }
            $limit = (!empty($_GET['limit']) && $_GET['limit'] <= 100) ? $_GET['limit'] : 15;
            $criterio->setLimit($limit);
            $start = (empty($_GET['start'])) ? 0 : $_GET['start'];
            $criterio->setStart($start);
            $order = (empty($_GET['order'])) ? 'DESC' : $_GET['order'];
            $criterio->setOrder($order);
            $sort = (!empty($_GET['sort']) && in_array($_GET['sort'], $campos['nome'])) ? $_GET['sort'] : ((empty($campos['sort'])) ? $campos['nome'][1] : $campos['sort']);
            $criterio->setSort($sort);
            $form = (!empty($campos['form'])) ? 1 : 0;
            $checks = (!empty($campos['checks'])) ? 1 : 0;
            $op = (!empty($campos['op'])) ? $campos['op'] : '';
            $norder = ($order == "ASC") ? "DESC" : "ASC";
            $colunas = count($campos['rotulo']);
            $colunas = (!empty($campos['checks'])) ? $colunas + 1 : $colunas;
            $colunas = (!empty($campos['botoes'])) ? $colunas + 1 : $colunas;
            $url_colunas = $url."?op=".$op."&limit=".$limit."&start=".$start.$busca_url.$precrit_url;
            $url_full_pg = $url."?op=".$op."&limit=".$limit."&sort=".$sort."&order=".$order.$busca_url.$precrit_url;
            $contar = $this->contar($criterio);
            $ret = '
		<style type="text/css">
		.hd {background-color: #c2cdd6; padding: 5px; font-weight: bold;}
		tr.bx td {background-color: #DFDFDF; padding: 5px; font-weight: bold; color: #000000}
		tr.hd td {background-image:url("images/bg.gif"); padding: 5px; font-weight: bold; border:1px solid #C0C0C0; color: #000000}
		tr.hd td.hds {background-image:url("images/bgs.gif"); padding: 5px; font-weight: bolder; border:1px solid #C0C0C0; border-top: 1px solid #000000; color: #000000}
		tr.hd td a{color: #1D5F9F}
		.fundo1 {background-color: #DFDFDF; padding: 4px;}
		tr.fundo1 td {background-color: #DFDFDF; padding: 4px; border:1px solid #C0C0C0;}
		.fundo2 {background-color: #E0E8EF; padding: 4px;}
		tr.fundo2 td {background-color: #E0E8EF; padding: 4px; border:1px solid #C0C0C0;}
		.neutro {background-color: #FFFFFF; padding: 4px;}
		tr.neutro td {background-color: #FFFFFF; padding: 4px; border:1px solid #9FD4FF;}
		</style>
		<script language="javascript" type="text/javascript">
	function exibe_esconde(tipo){
    var coisinha = document.getElementById(tipo);
   	if (coisinha.style.display == ""){
	     coisinha.style.display = "none";
   }
   else {
      coisinha.style.display = "";
   }
}
function esconde_menus(){
var els = document.getElementsByTagName("TD");
var elsLen = els.length;
var pattern = new RegExp("(^|\\s)bg5(\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if (pattern.test(els[i].className) && els[i].colSpan != 3 && els[i].colSpan != 4) {
			if(els[i].style.display==""){
			els[i].style.display="none";
			}else{
			els[i].style.display="";
			}
		}
	}
}
function changecheck(){
	var f = document.getElementById("update_form");
	var inputs = document.getElementsByTagName("input");
	for(var t = 0;t < inputs.length;t++){
		if(inputs[t].type == "checkbox" && inputs[t].id != "checkAll"){
		inputs[t].checked = !inputs[t].checked;
		inputs[t].onclick();
		}
	}
	return true;
}'.(($checks) ? '
function verificaChecks(){
var grp_sel = document.getElementById("group_action");
if(grp_sel.options[grp_sel.selectedIndex].value == 0) return true;
var inputs = document.getElementsByTagName("input");
	for(var t = 0;t < inputs.length;t++){
		if(inputs[t].type == "checkbox" && inputs[t].checked == true) return true;
	}
	alert("'.$campos['lang']['group_erro_sel'].'");
	return false;
}
function marcaCheck(linha, ckbx, classe){
var tr = document.getElementById(linha);
var valor = document.getElementById(ckbx).checked;
//alert(tr.onmouseout);
if(valor == true){
tr.className = "neutro";
tr.onmouseout = function(){};
return true;
}else{
tr.className = classe;
tr.onmouseout = function(){this.className=classe};
return true;
}
}
</script>' : "</script>");
            $ret .= (!empty($campos['noadminmenu'])) ? '
		<script language="javascript" type="text/javascript">
if (window.addEventListener)
window.addEventListener("load", esconde_menus, false)
else if (window.attachEvent)
window.attachEvent("onload", esconde_menus)
</script>' : '';
            $ret .= '
<table width="100%" border="0" cellspacing="0" class="outer">
<tr><td style="padding:5px; font-size:16px; border: 1px solid #C0C0C0; border-bottom:0px"><div style="font-size:12px; text-align:right; float:right">'.((empty($campos['nofilters']))? '<a href="javascript:void(0);"  onclick="exibe_esconde(\'busca\');">'.$campos['lang']['filtros'].'</a> - <a href="javascript:void(0);"  onclick="esconde_menus();">'.$campos['lang']['showhidemenu'].'</a>' : "").'</div><b>'.$campos['lang']['titulo'].'</b></td></tr>
<tr><td class="outer" style="background-color: #F3F2F2;"><div style="text-align: center;">';
            $ret .= "<form action='".$url."' method='GET' name='form_npag'>".$precrit_hidden."<b>".$campos['lang']['exibir']."&nbsp;&nbsp;<input type='text' name='limit' value='".$limit."' size='4' maxlength='3' style='text-align:center'>&nbsp;&nbsp;".$campos['lang']['por_pagina']."</b>";
            if (!empty($_GET['busca'])) {
                foreach ($_GET['busca'] as $k => $v) {
                    if($v != '' && $v != '-1' && !is_array($v)){
                        $ret .= "<input type='hidden' name='busca[".$k."]' value='".$v."'>";
                    }elseif (is_array($v)){
                        $ret .= "<input type='hidden' name='busca[".$k."][dday]' value='".$v['dday']."'>";
                        $ret .= "<input type='hidden' name='busca[".$k."][dmonth]' value='".$v['dmonth']."'>";
                        $ret .= "<input type='hidden' name='busca[".$k."][dyear]' value='".$v['dyear']."'>";
                        $ret .= "<input type='hidden' name='busca[".$k."][aday]' value='".$v['aday']."'>";
                        $ret .= "<input type='hidden' name='busca[".$k."][amonth]' value='".$v['amonth']."'>";
                        $ret .= "<input type='hidden' name='busca[".$k."][ayear]' value='".$v['ayear']."'>";
                    }
                }
            }
            $ret .= "<input type='hidden' name='op' value='".$op."'><input type='hidden' name='sort' value='".$sort."'><input type='hidden' name='order' value='".$order."'>";
            $ret .= "&nbsp;&nbsp;&nbsp;<input type='image' src='images/envia.gif' style='border:0px; background-color:none' align='absmiddle'></form>";
            $ret .= "<table width='100%' border='0' cellspacing='0'>";
            $ret.= "<tbody><tr><td colspan='".$colunas."' align='right'>".sprintf($campos['lang']['exibindo'], $start+1, ((($start+$limit) < $contar) ? $start+$limit : $contar), $contar)."</td></tr></tbody>";
            $ret .= "<tbody><tr class='hd'>";
            $ret.= ($checks) ? "<td align='center'><input type='checkbox' name='checkAll' id='checkAll' onclick='changecheck();'></td>" : "" ;
            foreach ($campos['rotulo'] as $k => $v) {
                $ret .= "<td nowrap='nowrap' align='center' ".(($sort == $campos['nome'][$k] && empty($campos['nosort'][$k])) ? "class='hds'" : '').">".((empty($campos['nosort'][$k])) ? "<A HREF='".$url_colunas."&sort=".$campos['nome'][$k]."&order=".$norder."'>".$v." ".(($sort == $campos['nome'][$k]) ? "<img src='images/".$order.".gif' align='absmiddle'>" : '')."</a></td>" : $v."</td>");
            }
            $ret.= (!empty($campos['botoes'])) ? "<td align='center'>".$campos['lang']['acao']."</td>" : "";
            $ret .="</tr></tbody>";
            if(empty($campos['nofilters'])){
                $ret.="<form action='".$url."' method='GET' name='form_busca'><tbody><tr id='busca' ".((!empty($_GET['busca'])) ? '' : "style='display:none'")." class='neutro'>";
                $ret.= ($checks) ? "<td>&nbsp;</td>" : "";
                foreach ($campos['rotulo'] as $k => $v) {
                    $ret .= "<td align='center' nowrap='nowrap'>";
                    switch ($campos['tipo'][$k]){
                        case "none":
                            break;
                        case "date":
                            $ret.= "<input type='text' name='busca[".$campos['nome'][$k]."][dday]' size='2' maxlength='2' value=".((!empty($_GET['busca'][$campos['nome'][$k]]['dday'])) ? $_GET['busca'][$campos['nome'][$k]]['dday'] : "")."> <input type='text' name='busca[".$campos['nome'][$k]."][dmonth]' size='2' maxlength='2' value=".((!empty($_GET['busca'][$campos['nome'][$k]]['dmonth'])) ? $_GET['busca'][$campos['nome'][$k]]['dmonth'] : "")."> <input type='text' name='busca[".$campos['nome'][$k]."][dyear]' size='2' maxlength='4' value=".((!empty($_GET['busca'][$campos['nome'][$k]]['dyear'])) ? $_GET['busca'][$campos['nome'][$k]]['dyear'] : "")."><br />";
                            $ret.= "<input type='text' name='busca[".$campos['nome'][$k]."][aday]' size='2' maxlength='2' value=".((!empty($_GET['busca'][$campos['nome'][$k]]['aday'])) ? $_GET['busca'][$campos['nome'][$k]]['aday'] : "")."> <input type='text' name='busca[".$campos['nome'][$k]."][amonth]' size='2' maxlength='2' value=".((!empty($_GET['busca'][$campos['nome'][$k]]['amonth'])) ? $_GET['busca'][$campos['nome'][$k]]['amonth'] : "")."> <input type='text' name='busca[".$campos['nome'][$k]."][ayear]' size='2' maxlength='4' value=".((!empty($_GET['busca'][$campos['nome'][$k]]['ayear'])) ? $_GET['busca'][$campos['nome'][$k]]['ayear'] : "").">";
                            break;
                        case "select":
                            $ret.="<select name='busca[".$campos['nome'][$k]."]'><option value='-1'>"._SELECT."</option>";
                            foreach ($campos['options'][$k] as $x => $y){
                                $ret.="<option value='".$x."'";
                                $ret.= (isset($_GET['busca'][$campos['nome'][$k]]) && $_GET['busca'][$campos['nome'][$k]] == $x) ? ' selected="selected"' : '';
                                $ret.=">".$y."</option>";
                            }
                            $ret.="</select>";
                            break;
                        case "simnao":
                            $ret.="<select name='busca[".$campos['nome'][$k]."]'><option value='-1'>"._SELECT."</option>";
                            $ret.="<option value='1'";
                            $ret.= (isset($_GET['busca'][$campos['nome'][$k]]) && $_GET['busca'][$campos['nome'][$k]] == 1) ? ' selected="selected"' : '';
                            $ret.=">"._YES."</option>";
                            $ret.="<option value='0'";
                            $ret.= (isset($_GET['busca'][$campos['nome'][$k]]) && $_GET['busca'][$campos['nome'][$k]] == 0) ? ' selected="selected"' : '';
                            $ret.=">"._NO."</option>";
                            $ret.="</select>";
                            break;
                        case "text":
                        default:
                            $ret.="<input type='text' name='busca[".$campos['nome'][$k]."]' value='".(isset($_GET['busca'][$campos['nome'][$k]]) ? $_GET['busca'][$campos['nome'][$k]] : '')."' size='".((isset($campos['tamanho'][$k])) ? $campos['tamanho'][$k]: 20)."'/>";
                    }
                    if (empty($campos['botoes']) && $k == count($campos['rotulo'])) {
                        $ret .= " <input type='image' src='images/envia.gif' style='border:0px; background-color:none' align='absmiddle'>";
                    }
                    $ret .= "</td>";
                }
                $ret.= (!empty($campos['botoes'])) ? "<td align='center'><input type='image' src='images/envia.gif' style='border:0px; background-color:none'></td>" : "";
                $ret.="</tr></tbody>";
                $ret.= $precrit_hidden."<input type='hidden' name='op' value='".$op."'><input type='hidden' name='sort' value='".$sort."'><input type='hidden' name='order' value='".$order."'><input type='hidden' name='limit' value='".$limit."'></form>";
            }
            $registros = (empty($campos['join'])) ? $this->PegaTudo($criterio) : $this->PegaTudo($criterio, true, $campos['join']);
            if (!$registros || count($registros) == 0) {
                $ret.= "<tbody><tr><td colspan='".$colunas."'><h2>".$campos['lang']['semresult']."</h2></td></tr></tbody>";
                $ret.="<tbody><tr class='bx'><td colspan='".$colunas."' align='left'>".$this->paginar($url_full_pg,$criterio, $precrit_url)."</td></tr></tbody>";
            }else{
                $ret.= ($form || $checks) ? "<form action='".$url."' method='POST' name='update_form' id='update_form' ".(($checks) ? "onsubmit='return verificaChecks()'" : "").">" : '';
                foreach ($registros as $reg) {
                    $eod = (!isset($eod) || $eod == "fundo1") ? "fundo2" : "fundo1";
                    $ret.= "<tbody><tr id='tr_reg_".$reg->getVar($reg->id)."' class='".$eod."' onmouseover='this.className=\"neutro\";' onmouseout='this.className=\"".$eod."\"'>";
                    $ret.= ($checks) ? "<td align='center'><input type='checkbox' name='checks[".$reg->getVar($reg->id)."]' id='checks[".$reg->getVar($reg->id)."]' value='1' onclick='marcaCheck(\"tr_reg_".$reg->getVar($reg->id)."\", \"checks[".$reg->getVar($reg->id)."]\", \"".$eod."\");'></td>" : "" ;
                    foreach ($campos['rotulo'] as $l => $f){
                        $ret.= "<td>";
                        switch ($campos['tipo'][$l]){
                            case "none":
                                $ret.= (empty($campos['show'][$l])) ? $reg->getVar($campos['nome'][$l]) : eval('return '.$campos["show"][$l].';');
                                break;
                            case "date":
                                $ret.= (!empty($campos['show'][$l]) ? eval('return '.$campos["show"][$l].';') : (($reg->getVar($campos['nome'][$l]) != 0 && $reg->getVar($campos['nome'][$l]) != "") ? date(_SHORTDATESTRING, $reg->getVar($campos['nome'][$l])) : ""));
                                break;
                            case "select":
                                if($form && empty($campos['show'][$l])){
                                    $ret.="<select name='campos[".$reg->getVar($reg->id)."][".$campos['nome'][$l]."]'>";
                                    foreach ($campos['options'][$l] as $x => $y){
                                        $ret.="<option value='".$x."'";
                                        $ret.= ($reg->getVar($campos['nome'][$l]) == $x) ? ' selected="selected"' : '';
                                        $ret.=">".$y."</option>";
                                    }
                                    $ret.="</select>";
                                }elseif (!empty($campos['show'][$l])){
                                    $ret.= eval('return '.$campos["show"][$l].';');
                                }else{
                                    $ret.= 	(isset($campos['options'][$l][$reg->getVar($campos['nome'][$l])])) ? $campos['options'][$l][$reg->getVar($campos['nome'][$l])]:$reg->getVar($campos['nome'][$l]) ;
                                }
                                break;
                            case "simnao":
                                if($form && empty($campos['show'][$l])){
                                    $ret.="<select name='campos[".$reg->getVar($reg->id)."][".$campos['nome'][$l]."]'>";
                                    $ret.="<option value='1'";
                                    $ret.= ($reg->getVar($campos['nome'][$l]) == 1) ? ' selected="selected"' : '';
                                    $ret.=">"._YES."</option>";
                                    $ret.="<option value='0'";
                                    $ret.= ($reg->getVar($campos['nome'][$l]) == 0) ? ' selected="selected"' : '';
                                    $ret.=">"._NO."</option>";
                                    $ret.="</select>";
                                }elseif (!empty($campos['show'][$l])){
                                    $ret.= eval('return '.$campos["show"][$l].';');
                                }else{
                                    $ret.= ($reg->getVar($campos['nome'][$l]) == 1) ? _YES : (($reg->getVar($campos['nome'][$l]) == 0) ? _NO : $reg->getVar($campos['nome'][$l]));
                                }
                                break;
                            case "text":
                            default:
                                $ret.= ($form && empty($campos['show'][$l])) ? "<input type='text' name='campos[".$reg->getVar($reg->id)."][".$campos['nome'][$l]."]' value='".$reg->getVar($campos['nome'][$l])."' size='".((isset($campos['tamanho'][$l])) ? $campos['tamanho'][$l]: 20)."'/>" : (!empty($campos['show'][$l]) ? eval('return '.$campos["show"][$l].';'): $reg->getVar($campos['nome'][$l]));
                        }

                        $ret.="</td>";
                    }
                    //$ret.= "<td nowrap='nowrap'><a href='".$url."?op=".$op."_editar&".$reg->id."=".$reg->getVar($reg->id)."'><img src='images/editar.gif'></a> <a href='".$url."?op=".$op."_deletar&".$reg->id."=".$reg->getVar($reg->id)."'><img src='images/deletar.gif'></a> ".((!empty($campos['print'])) ? "<a href='".$url."?op=".$op."_imprime&".$reg->id."=".$reg->getVar($reg->id)."' target='_blank'><img src='images/imprime.gif'></a>" : '');
                    if(!empty($campos['botoes'])){
                        $ret.= "<td nowrap='nowrap'>";
                        if (is_array($campos['botoes'])) {
                            foreach ($campos['botoes'] as $b) {
                                $ret .= "<a href='".$b['link']."&".$reg->id."=".$reg->getVar($reg->id)."' title='".$b['texto']."'><img src='".$b['imagem']."' alt='".$b['texto']."'></a> ";
                            }
                        }
                        $ret.="</td>";
                    }
                    $ret.="</tr></tbody>";
                }
                if($form || $checks){
                    $ret.= "<tbody><tr><td colspan='".$colunas."'>";
                    $ret.= $precrit_hidden."<input type='hidden' name='sort' value='".$sort."'><input type='hidden' name='order' value='".$order."'><input type='hidden' name='limit' value='".$limit."'><input type='hidden' name='start' value='".$start."'>";
                    if (!empty($_GET['busca'])) {
                        foreach ($_GET['busca'] as $k => $v) {
                            if($v != '' && $v != '-1' && !is_array($v)){
                                $ret .= "<input type='hidden' name='busca[".$k."]' value='".$v."'>";
                            }elseif (is_array($v)){
                                $ret .= "<input type='hidden' name='busca[".$k."][dday]' value='".$v['dday']."'>";
                                $ret .= "<input type='hidden' name='busca[".$k."][dmonth]' value='".$v['dmonth']."'>";
                                $ret .= "<input type='hidden' name='busca[".$k."][dyear]' value='".$v['dyear']."'>";
                                $ret .= "<input type='hidden' name='busca[".$k."][aday]' value='".$v['aday']."'>";
                                $ret .= "<input type='hidden' name='busca[".$k."][amonth]' value='".$v['amonth']."'>";
                                $ret .= "<input type='hidden' name='busca[".$k."][ayear]' value='".$v['ayear']."'>";
                            }
                        }
                    }
                    $ret.="<input type='hidden' name='op' value='".$op."'>&nbsp;<br />";
                    if($checks){
                        $ret .= $campos['lang']['group_action'] . " <select name='group_action' id='group_action'><option value='0'>"._SELECT."</option>";
                        $ret .= (!empty($campos['group_del'])) ? "<option value='group_del'>".$campos['lang']['group_del']."</option>" : "";
                        if(!empty($campos['group_action'])){
                            foreach ($campos['group_action'] as $grp) {
                                $ret .= "<option value='".$grp['valor']."'>".$grp['texto']."</option>";
                            }
                        }
                        $ret .= "</select> ";
                    }
                    $ret.="<input type='submit' value='"._SUBMIT."'><br />&nbsp;</td></tr></tbody></form>";
                }
                $ret.= (!empty($campos['soma'])) ? "<tbody><tr class='bx'><td colspan='".$colunas."' align='right'>Total: R$ ".number_format($this->soma($criterio, $campos['soma']), 2, ",", ".")."</td></tr></tbody>" : "";
                $ret.="<tbody><tr class='bx'><td colspan='".$colunas."' align='left'>".$this->paginar($url_full_pg, $criterio, $precrit_url)."</td></tr></tbody>";
            }
            $ret.="</table></div></td></tr></table><br />";
            return $ret;
        }

        function contar($criterio=null){
            $sql = 'SELECT COUNT(*) FROM '.$this->tabela;
            if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
                $sql .= ' '.$criterio->renderWhere();
            }
            $result = $this->db->query($sql);
            if (!$result) {
                return 0;
            }
            list($count) = $this->db->fetchRow($result);
            return $count;
        }

        function soma($criterio=null, $campo){
            $sql = 'SELECT SUM('.$campo.') FROM '.$this->tabela;
            if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
                $sql .= ' '.$criterio->renderWhere();
            }
            $result = $this->db->query($sql);
            if (!$result) {
                return 0;
            }
            list($soma) = $this->db->fetchRow($result);
            return $soma;
        }

        // Retorna a paginação pronta
        function paginar($link, $criterio=null, $precrit_url=null){
            $ret = '';
            $order = 'ASC';
            $sort = $this->id;
            if (isset($criterio) && is_subclass_of($criterio, 'criteriaelement')) {
                $limit = $criterio->getLimit();
                $start = $criterio->getStart();
                if ($criterio->getSort() != '') {
                    $order = $criterio->getOrder();
                    $sort = $criterio->getSort();
                }
            }else{
                $limit = 15;
                $start = 0;
            }
            $todos = $this->contar($criterio);
            $total = ($todos % $limit == 0) ? ($todos/$limit) : intval($todos/$limit)+1;
            $pg = ($start) ? intval($start/$limit)+1 : 1;
            $ret.= (!empty($_GET['busca'])) ? "<input type=button value='"._ALL."' onclick=\"document.location= '".$_SERVER['PHP_SELF']."?limit=".$limit."&order=".$order."&sort=".$sort."&op=".$GLOBALS["op"].$precrit_url."'\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            for($i=1;$i<=($total);$i++ )
            {
                $start = $limit * ($i-1);
                if($i == $pg){
                    $ret .=  "<span style='font-weight: bold; color: #CF0000; font-size: 15px;'> $i </span>";
                }elseif(($pg - 10) > $i){
                    if (!isset($pg1)) {
                        $ret .= ("<A HREF='".$link."&start=".$start."'>1</a>. . .");
                        $pg1 = true;
                    }
                    continue;
                }elseif ($i < ($pg + 10)){
                    $ret .= (" <A HREF='".$link."&start=".$start."'>".$i."</a> ");
                }else{
                    $ret .= (". . . <A HREF='".$link."&start=".(($todos % $limit == 0) ? $todos - $limit : $todos-($todos % $limit))."'>".$total."</a>");
                    break;
                }
                if( $i!=$total ){
                    $ret .= ("|");
                }
            }
            return $ret;
        }
    }
}
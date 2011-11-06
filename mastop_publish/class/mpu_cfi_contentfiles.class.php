<?php
### =============================================================
### Mastop InfoDigital - Paixуo por Internet
### =============================================================
### Classe para manipulaчуo de pсginas
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital й 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_geral.class.php";
class mpu_cfi_contentfiles extends mpu_geral {
	function mpu_cfi_contentfiles($id=null){
		$this->db =& Database::getInstance();
		$this->tabela = $this->db->prefix(MPU_MOD_TABELA4);
		$this->id = "cfi_10_id";
		$this->initVar("cfi_10_id", XOBJ_DTYPE_INT);
		$this->initVar("cfi_30_nome", XOBJ_DTYPE_TXTBOX);
		$this->initVar("cfi_30_arquivo", XOBJ_DTYPE_TXTBOX);
		$this->initVar("cfi_30_mime", XOBJ_DTYPE_TXTBOX);
		$this->initVar("cfi_10_tamanho", XOBJ_DTYPE_INT, 0);
		$this->initVar("cfi_12_exibir", XOBJ_DTYPE_INT, 1);
		$this->initVar("cfi_22_data", XOBJ_DTYPE_INT, 0);
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
				$this->load(intval($id));
			}
		}

	}
	function deletaArquivo(){
		if (file_exists(MPU_HTML_PATH."/".$this->getVar("cfi_30_arquivo"))) {
			@unlink(MPU_HTML_PATH."/".$this->getVar("cfi_30_arquivo"));
			return true;
		}
		return false;
	}
	function pegaMimes(){
		$sql = 'SELECT cfi_30_arquivo, cfi_30_mime FROM '.$this->tabela.' GROUP BY cfi_30_mime';
		$resultado = $this->db->query($sql);
		$this->total = $this->db->getRowsNum($resultado);
		if ($this->total > 0){
			while ( $linha = $this->db->fetchArray($resultado) ) {
				$ext = (substr($linha['cfi_30_arquivo'], -4,1) == ".") ? substr($linha['cfi_30_arquivo'], -4) : substr($linha['cfi_30_arquivo'], -5);
				$ret[$linha['cfi_30_mime']] = $linha['cfi_30_mime']." (".$ext.")";
			}
			return $ret;
		}else{
			return array();
		}
	}
	function listaPaginas(){
		$ret = array(0=>MPU_ADM_SELECIONE);
		$sql = 'SELECT cfi_30_nome, cfi_30_arquivo FROM '.$this->tabela;
		$resultado = $this->db->query($sql);
		$this->total = $this->db->getRowsNum($resultado);
		if ($this->total > 0){
			while ( $linha = $this->db->fetchArray($resultado) ) {
				$ext = (substr($linha['cfi_30_arquivo'], -4,1) == ".") ? substr($linha['cfi_30_arquivo'], -4) : substr($linha['cfi_30_arquivo'], -5);
				$ret[$linha['cfi_30_arquivo']] = $linha['cfi_30_nome']." (".$ext.")";
			}
		}
		return $ret;
	}
	function delete()
	{
		include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_mpb_mpublish.class.php";
		$mpu_classe = new mpu_mpb_mpublish();
		$criterio = new Criteria("mpb_30_arquivo", $this->getVar("cfi_30_arquivo"));
		$mpb_todos = $mpu_classe->PegaTudo($criterio);
		if ($mpb_todos) {
			foreach ($mpb_todos as $v){
				$mpb_10_id = $v->getVar("mpb_10_id");
				mpu_apagaPermissoes($mpb_10_id);
				$v->delete();
				if($v->tem_subcategorias($mpb_10_id)){
					mpu_apagaPermissoesPai($mpb_10_id);
					$mpu_classe->deletaTodos(new Criteria("mpb_10_idpai", $mpb_10_id));
				}
				$v->delete();
			}
		}
		$sql = sprintf("DELETE FROM %s WHERE ".$this->id." = %u", $this->tabela, $this->getVar($this->id));
		if ( !$this->db->query($sql) ) {
			return false;
		}
		$this->afetadas = $this->db->getAffectedRows();
		return true;
	}
}
?>
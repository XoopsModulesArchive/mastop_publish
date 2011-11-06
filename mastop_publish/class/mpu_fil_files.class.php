<?php
### =============================================================
### Mastop InfoDigital - Paixуo por Internet
### =============================================================
### Classe para manipulaчуo de arquivos
### =============================================================
### Developer: Fernando Santos (topet05), fernando@mastop.com.br
### Copyright: Mastop InfoDigital й 2003-2006
### -------------------------------------------------------------
### www.mastop.com.br
### =============================================================
### $Id$
### =============================================================
include_once XOOPS_ROOT_PATH."/modules/".MPU_MOD_DIR."/class/mpu_geral.class.php";
class mpu_fil_files extends mpu_geral {
	function mpu_fil_files($id=null){
		$this->db =& Database::getInstance();
		$this->tabela = $this->db->prefix(MPU_MOD_TABELA2);
		$this->id = "fil_10_id";
		$this->initVar("fil_10_id", XOBJ_DTYPE_INT);
		$this->initVar("fil_30_nome", XOBJ_DTYPE_TXTBOX);
		$this->initVar("fil_30_arquivo", XOBJ_DTYPE_TXTBOX);
		$this->initVar("fil_30_mime", XOBJ_DTYPE_TXTBOX);
		$this->initVar("fil_10_tamanho", XOBJ_DTYPE_INT, 0);
		$this->initVar("fil_12_exibir", XOBJ_DTYPE_INT, 1);
		$this->initVar("fil_22_data", XOBJ_DTYPE_INT, 0);
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
				$this->load(intval($id));
			}
		}

	}
	function deletaArquivo(){
		if (file_exists(MPU_FILES_PATH."/".$this->getVar("fil_30_arquivo"))) {
			@unlink(MPU_FILES_PATH."/".$this->getVar("fil_30_arquivo"));
			return true;
		}
		return false;
	}
	function pegaMimes(){
		$sql = 'SELECT fil_30_arquivo, fil_30_mime FROM '.$this->tabela.' GROUP BY fil_30_mime';
		$resultado = $this->db->query($sql);
		$this->total = $this->db->getRowsNum($resultado);
		if ($this->total > 0){
			while ( $linha = $this->db->fetchArray($resultado) ) {
				$ext = (substr($linha['fil_30_arquivo'], -4,1) == ".") ? substr($linha['fil_30_arquivo'], -4) : substr($linha['fil_30_arquivo'], -5);
				$ret[$linha['fil_30_mime']] = $linha['fil_30_mime']." (".$ext.")";
			}
			return $ret;
		}else{
			return array();
		}
	}
}
?>
<?
/*
 Classe de manipulação de dados
 @author Pedro Henrique Rosa Barbosa
 @version 16/05/2006
*/

class Manipula {
	
	var $tabela;
	var $chavep;
	var $campos;
	var $where;
	
	var $mode;
	var $after_update;
	var $after_insert;
	
	function Manipula() {

		$this->campos   = array();
		$this->mode 	= (isSet($_GET['i'])) ? "e" : "i" ;
	}
	function setAfterUpdate($valor) {
	
		$this->after_update = $valor;
	}
	function setAfterInsert($valor) {
		
		$this->after_insert = $valor;
	}
	function setTabela($tabela){
		
		$this->tabela = $tabela;
	}
	function setChave($chave) {
		
		$this->chavep = $chave;
	}
	function setWhere($where) {
		
		$this->where = $where;
	}
	function usaCampo($nome) {

		$campo = $this->getCampo($nome);
		if ($campo['tipo']=="passwd") {
			return ($_POST[$nome] != "");
		}
		else {
			return true;
		}
	}
	function formataCampo($nome_campo) {
		
		$campo = $this->getCampo($nome_campo);
		
		if ($_POST[$nome_campo]=="") {
			return "NULL";
		}
		if ($campo['tipo']=="string") {
			return "\"".$_POST[$nome_campo]."\"";
		}
		else if ($campo['tipo']=="nontxt") {
			return $_POST[$nome_campo];
		}
		else if ($campo['tipo']=="date") {
			return "\"".inData($_POST[$nome_campo])."\"";
		}
		else if ($campo['tipo']=="passwd") {
			return "\"".$_POST[$nome_campo]."\"";
		}
	}
	function insere() {

		if (isSet($_POST['trigger'])&&$_POST['trigger']=="insere"){

			$nomes_campos = array();
			foreach($this->campos as $i => $campo) {
				if ($this->usaCampo($campo['nome'])) { 
					array_push($nomes_campos,$campo['nome']);
				}
			}
			
			$valores_campos = array();
			foreach($this->campos as $i => $campo) {
				if ($this->usaCampo($campo['nome'])) {
					array_push($valores_campos,$this->formataCampo($campo['nome']));
				}
			}
			
			$sql_statement  = "INSERT INTO ".$this->tabela." " ;
			$sql_statement .= "(".implode(",",$nomes_campos).")" ;
			$sql_statement .= " VALUES " ;
			$sql_statement .= "(".implode(",",$valores_campos).")" ;
			
			
			$conexao = new Conexao;
			$conexao->open();
			
			$conexao->execute($sql_statement);

			$conexao->close();
			
			header("Location: ".$this->after_insert);
		}
	}
	
	function atualiza() {

		if (isSet($_POST['trigger'])&&$_POST['trigger']=="edita"){
		
			$tmp01 = array();
			for ($i=0;$i<count($this->campos);$i++){
				$campo = $this->campos[$i];
				if ($this->usaCampo($campo['nome'])) { 
					array_push($tmp01,$campo['nome']." = ".$this->formataCampo($campo['nome']));
				}
			}

			$sql_statement  = "UPDATE ".$this->tabela." SET ";			
			$sql_statement .= implode(",",$tmp01);
			$sql_statement .= " WHERE ".$this->chavep." = ".$_GET['i']." ";
			$sql_statement .= ($this->where=="") ? "" : " AND ".$this->where ;
			
			$conexao = new Conexao;
			$conexao->open();
			
			$conexao->execute($sql_statement);
			//echo $sql_statement;
			$conexao->close();

			header("Location: ".$this->after_update);
		}
	}
	function execManipula() {

		if ($this->mode=="e"){
			$conexao = new Conexao;
			$conexao->open();
			
			$nomes_campos = array();
			foreach($this->campos as $i => $campo) {
				array_push($nomes_campos,$campo['nome']);
			}
			
			$tmp01 = ($this->where=="") ? "" : " AND ".$this->where ;
			$dados = $conexao->result("
									   SELECT ".implode(",",$nomes_campos)." 
									   FROM  ".$this->tabela."
									   WHERE ".$this->chavep." = ".$_GET['i'].
									   $tmp01
									   );
			   
			foreach($this->campos as $i => $campo) {
				$this->setCampo($campo['nome'],$dados[0][$campo['nome']]); 
			}
			
			$conexao->close();
			
			$this->atualiza();
		}
		else {
			$this->insere();
		}
	}
	function addCampo($nome_campo,$valor_padrao,$type=-1) {
	
		$tmp = array (
					 "nome" 	=> $nome_campo,
					 "valor"	=> $valor_padrao,
					 "tipo"		=> $type
					 );
					 
		array_push($this->campos,$tmp);
	}
	function setCampo($nome_campo,$valor) {

		foreach($this->campos as $i => $campo) {
			if ($campo['nome']==$nome_campo){
				$this->campos[$i]['valor']=$valor;
				break;
			}
		}
	}
	function getValorCampo($nome_campo) {
	
		foreach($this->campos as $i => $campo) {
			if ($campo['nome']==$nome_campo){
				if ($campo['tipo']=="date") {
					return ($campo['valor']!="") ? cnData($campo['valor']) : "" ;
				}
				else {
					return $campo['valor'];
				}
			}
		}
		
		return NULL;
	}
	function getCampo($nome_campo) {
	
		foreach($this->campos as $i => $campo) {
			if ($campo['nome']==$nome_campo){
				return $campo;
			}
		}
		
		return NULL;
	}
	function getStringParamList() {
	
		$res = array();
		foreach($this->campos as $i => $campo) {
			
			$nome = $campo['nome'];
			array_push($res,"$nome='+\$F('$nome').replace('%',escape('%'))+'");
		}
		
		return implode("&",$res);
	}
}

function inData($data) {
	
	$datahora = explode(" ",$data);
	
	$data_arr = explode("/",$datahora[0]);
	$data_cnv = $data_arr[2]."-".$data_arr[1]."-".$data_arr[0];
	
	return (count($datahora) == 1) ? $data_cnv : $data_cnv." ".$datahora[1];
}
function cnData($data) {
	
	$datahora = explode(" ",$data);
	
	$data_arr = explode("-",$datahora[0]);
	$data_cnv = $data_arr[2]."/".$data_arr[1]."/".$data_arr[0];
	
	return (count($datahora) == 1) ? $data_cnv : $data_cnv." ".$datahora[1];
}

?>

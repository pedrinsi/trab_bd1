<?
/*
 Classe de listagem de dados
 @author Pedro Henrique Rosa Barbosa
 @version 08/05/2006
*/
class Listagem {

	var $tabela; // Tabela aonde será feita a listagem
	var $chavep; // Chave primária da tabela
	var $campos; // Campos que serão exibidos
	
	var $joins; // Gerencia os inner joins
	
	var $where;  // Criterio adicional
	
	var $buscac;  // Campos incluidos no criterio de busca
	var $procura; // Campo de busca
	
	var $ordena; // Critério de ordenação

	var $pagina; // Pagina corrente da listagem
	var $regpag; // Quantidade de registros por página
	var $totalp; // Total de Paginas
	var $totalr; // Total de Registros
	var $listap; // Tamanho da lista de páginas

	var $result; // Resultset da consulta

	var $disable_remove;

	function Listagem() {
		$this->regpag  = 10; // Minimo
		$this->listap  = 5;  // Somente numeros impares
		$this->campos  = array();
		$this->buscac  = array();
		$this->joins   = array();
		$this->procura = (isSet($_GET['s'])) ? $_GET['s'] : "" ;
		$this->pagina  = (isSet($_GET['p'])) ? $_GET['p'] : 1 ;
	}
	
	function removeRegistros(){
		if (isSet($_POST['lst_remove'])) {
			$lista = explode(",",$_POST['lst_remove']);
			$conexao = new Conexao;
			$conexao->open();
			foreach($lista as $i => $registro){
				$conexao->execute("DELETE FROM $this->tabela WHERE $this->chavep = $registro");
			}
			$conexao->close();
		}
	}

	function addCampo($nome_campo,$largura,$menu,$tipo,$aparece=true,$alias=NULL) {
		
		if ($alias==NULL) { $alias = $nome_campo; }

		$tmp = array (
					 "nome" 	=> $nome_campo,
					 "largura" 	=> $largura,
					 "menu" 	=> $menu,
					 "tipo" 	=> $tipo,
					 "aparece"	=> $aparece,
					 "alias"	=> $alias
					 );

		array_push($this->campos,$tmp);
	}
	function addCampoBusca($nome_campo,$tipo,$periodo=NULL){
	
		$tmp = array (
					 "nome" => $nome_campo,
					 "tipo" => $tipo
					 );
					 
		array_push($this->buscac,$tmp);
	}
	function addJoin($tabela,$chavep,$chavee) {
		
		$tmp = array (
						"tabela" => $tabela,
						"chavep" => $chavep,
						"chavee" => $chavee
					 );
		array_push($this->joins,$tmp);
	}
	function setChave($chavep) {

		$this->chavep = $chavep;
	}

	function setRegistrosPagina($regpag) {

		$this->regpag = $regpag;
	}
	function setTabela($tabela) {

		$this->tabela = $tabela;
	}
	function setWhere($where) {

		$this->where = $where;
	}

	function ehPrimeiraPag() {
		
		return ($this->pagina == 1);
	}
	function ehUltimaPag() {
		
		return ($this->pagina == $this->totalp);
	}
	function execListagem() {

		$conexao = new Conexao;
		$conexao->open();

		//Iniciando o select
		$sqlStatement = "SELECT ";
		
		// Separando os campos
		$tmp1 = array($this->chavep);
		$tmp2 = array();
		foreach($this->campos as $i => $campo) {
			array_push($tmp1,$campo["nome"]);
		}
		$sqlStatement .= implode(",",$tmp1);
		
		// Selecionando a tabela
		$sqlStatement .= " FROM $this->tabela ";
		
		// Preparando os joins
		
		foreach($this->joins as $i => $join) {
			$tb = $join['tabela'];
			$cp = $join['chavep'];
			$ce = $join['chavee'];
			$sqlStatement .= "INNER JOIN $tb ON $cp = $ce ";
		}
		
		// Criterios de busca
		if (isSet($_GET['s']) && $_GET['s']!="") {
			$tmp2 = array();
			foreach($this->buscac as $i => $campo) {
				$expr = $campo['nome']." LIKE '%".$_GET['s']."%'";
				array_push($tmp2,$expr);
			}
		}
		$sqlStatement .= (count($tmp2)>0) ? 
						 "WHERE (".implode(" OR ",$tmp2).")" : "";

		if (count($tmp2)>0&&$this->where!="") {
			$sqlStatement .= " AND (".$this->where.")";
		}
		if (count($tmp2)==0&&$this->where!="") {
			$sqlStatement .= " WHERE (".$this->where.")";
		}

		// Criterios de ordenação
		$sqlStatement .= " ORDER BY ";
		$sqlStatement .= (isSet($_GET['o1'])) ? 
						 $_GET['o1'] : 
						 $this->chavep ;
		$sqlStatement .= (isSet($_GET['o2'])&&$_GET['o2']==1) ? 
						 " DESC " : 
						 " " ;

		// Paginação

		if ($this->regpag != -1) {
			$this->totalr = count($conexao->result($sqlStatement));
			
			$this->totalp = ceil($this->totalr/$this->regpag);
			$this->totalp = ($this->totalp==0) ? 1 : $this->totalp ;
			
			$inf =  ($this->pagina-1)*$this->regpag;
			
			$sqlStatement .= " LIMIT ".$inf.",".$this->regpag ;
		}
		//echo $sqlStatement;
		$this->result = $conexao->result($sqlStatement);
		$conexao->close();
	
	}
	
	function getColTexto($nome_campo,$ordena=TRUE) {

		$menu=NULL;
		$alias=NULL;
		foreach($this->campos as $i => $campo) {
			
			if ($campo['nome']==$nome_campo) { 
				$menu=$campo['menu'];
				$alias=$campo['alias'];
				break; 
			}
		}
		
		$query_string = setQueryString($_SERVER['QUERY_STRING'],'o1',$alias) ;
		$image_ordena = NULL;
		
		if (isSet($_GET['o1'])&&$_GET['o1']==$alias) {
			$query_string = setQueryString($query_string,'o2',!$_GET['o2']);
			$image_ordena = (isSet($_GET['o2'])&&$_GET['o2']==1) ? 
							"<img src=\"img/listagem_seta_up.gif\" alt=\"\" />":
							"<img src=\"img/listagem_seta_do.gif\" alt=\"\" />";
		}
					
		return ($ordena) ? 
				"<a href=\"".$_SERVER['SCRIPT_NAME']."?".$query_string."\">$menu</a>&nbsp;".$image_ordena :
				$menu;
	}
	
	function getListaPags() {
		
		// ALGORITMO LISTA DE PAGINAS - INICIO
			
		$arrPaginas = array();
			
		$inf = NULL;
		$sup = NULL;
			
		$tmp01 = floor($this->listap/2);
			
		// se o highlight estiver no meio da seleção
		if ($this->pagina - $tmp01 >= 1 && $this->pagina + $tmp01 <= $this->totalp){
			$inf = $this->pagina-$tmp01;
			$sup = $this->pagina+$tmp01;
		}
		else {
			// se estiver no lado esquerdo
			if ($this->pagina - $tmp01 < 1) {
				$inf = 1;
				$sup = ($this->totalp < $this->listap) ? $this->totalp : $this->listap ;
			}
			// se estiver no lado direito
			else if ($this->pagina + $tmp01 > $this->totalp) {
				$inf = ($this->totalp < $this->listap) ? 1 : $this->totalp-($this->listap-1) ;
				$sup = $this->totalp;
			}				
		}
			
		for ($x=$inf,$y=0;$x<=$sup;$x++,$y++) {
			$arrPaginas[$y] = $x;
		}
		
		return $arrPaginas;
				
		// ALGORITMO LISTA DE PAGINAS - FIM
	}
	
	function getTotalReg() {
		return count($this->result);
	}
}

function setQueryString($querys,$campo,$valor) {
	
	$tmp1 = ($querys=="") ? array() : explode("&",$querys);
	$res  = array();
	$pas  = false;
	 
	foreach($tmp1 as $i => $linha) {
		$tmp2 = explode("=",$linha);
		if ($tmp2[0]==$campo){
			$pas  = true;
			$tmp2[1]=$valor;
		}
		$tmp2 = implode("=",$tmp2);
		array_push($res,$tmp2);
	}
	if (!$pas){
		array_push($res,"$campo=$valor");	
	}
	
	//echo $res[1];
	return  implode("&",$res);

}
?>
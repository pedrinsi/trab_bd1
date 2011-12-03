<?
/*
 Classe de conexao ao banco de dados
 @author Pedro Henrique Rosa Barbosa
 @version 14/03/2006
*/
class Conexao {

	var $banco_host;	// Host do banco
	var $banco_dbas; 	// Nome do banco
	var $banco_user;	// Usuario do banco
	var $banco_senh;	// Senha do banco

	var $banco_conn;	// Variavel da conexao
	
	function Conexao() {
		$this->banco_host = "localhost";
		$this->banco_dbas = "joao_teste";
		$this->banco_user = "root";
		$this->banco_senh = "poo2";
	}

	// Abrir conexao com banco
	function open() {
	
		if ($this->banco_conn) { 
			die("Erro funcao open().O banco já está aberto."); 
		}
		else {
			$this->banco_conn = mysql_connect($this->banco_host,$this->banco_user,$this->banco_senh) or die (mysql_error());
			if (!mysql_select_db($this->banco_dbas)) {
				die(mysql_error());
			}
		}
	}
	
	//Fechar conexao com banco
	function close() {
	/*
		if (!$this->banco_conn) {
			die("Erro funcao close(). O banco não está aberto");
		}
		mysql_close($this->banco_conn);
	*/
	}
	
	// Retorna resultado de uma busca
	function result($sql_query) {

		$arr_result = array();
		$sql_result = mysql_query($sql_query) or die(mysql_error());
		
		$row_result = mysql_num_rows($sql_result);
		
		for ($x=0;$x<$row_result;$x++) {
			$arr_result[$x] = mysql_fetch_array($sql_result);
		}
		
		mysql_free_result($sql_result);
		
		return $arr_result;
	}
	
	// Executa atualizações no banco
	function execute($sql_query) {
		
		mysql_query($sql_query) or die(mysql_error());
	}
}
	
	function registra_log($secao,$acao,$mensagem,$usuario=NULL){
		$conexao = new Conexao;
		$conexao->open();
		
		$usuario = ($usuario == NULL) ? $_SESSION['us_usuario'] : $usuario ;
		
		$conexao->execute("
			INSERT INTO is_log_geral(
									secao,
									funcao,
									mensagem,
									usuario,
									ip,
									data_hora
									) VALUES (
									'$secao',
									'$acao',
									'$mensagem',
									'".$usuario."',
									'".getenv('REMOTE_ADDR')."',
									CONCAT(CURDATE(),' ',CURTIME())
									)
		");
		
		
		$conexao->close();
	}
	
	function do_post_request($data, $optional_headers = null)
	{
	  $url = 'http://intranet.infinitech.com.br/_acesso_remoto.php' ;
	  
	  $params = array('http' => array(
				  'method' => 'POST',
				  'content' => http_build_query($data)
				));
	  if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	  }
	  $ctx = stream_context_create($params);
	  $fp = @fopen($url, 'rb', false, $ctx);
	  if (!$fp) {
		throw new Exception("Problem with $url, $php_errormsg");
	  }
	  $response = @stream_get_contents($fp);
	  if ($response === false) {
		throw new Exception("Problem reading data from $url, $php_errormsg");
	  }
	  return $response;
	}
	
?>
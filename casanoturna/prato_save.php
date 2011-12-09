<?php
	include("public/lib/_conexao.php");

	$conexao = new Conexao;
	$conexao->open();
	
	// Pega as informaçoes do post do prato
	$materias_primas = array();
	$materias_primas = $_POST['materias_primas'];
	if ($materias_primas != "") {
	
		$pnome = $_POST['nome']; //print_r($pnome);
		$pcodigo = $_POST['codigo']; //print_r($pcodigo);
		$pcusto = $_POST['custo_preparo'];// print_r($pcusto);
		$ptempo = $_POST['tempo_preparo']; //print_r($ptempo);
		// Salva as informaçoes do prato na tabela PRATO
		$consulta = $conexao->execute("
			INSERT INTO 
				prato (nome,codigo,custo_preparo,tempo_preparo)
			VALUES
				('$pnome','$pcodigo',$pcusto,'$ptempo')
		");
		
		// Busca do id do ultimo prato q foi salvo que no caso é o de cima =P
		$ultimo_id = $conexao->result("
			SELECT 
				MAX(id)
			FROM prato
		");
		$prato_id_fk = $ultimo_id[0][0];
		
		// Salva as materias prima relacionado ao prato
		foreach ($materias_primas as $i => $materia_prima) {

			$id_mp = $materia_prima['id_materia_prima'];
			$quantidade_mp = $materia_prima['quantidade_utilizada'];
			
			$consulta = $conexao->execute("
				INSERT INTO 
					pratos_materias_primas (id_prato,id_materia_prima,quantidade_utilizada)
				VALUES
					($prato_id_fk,$id_mp,$quantidade_mp)
			");
			
			$estoque_inicial = $conexao->result("
				SELECT 
					estoque
				FROM materia_prima
				WHERE id = $id_mp
			");

			$estoque_final = $estoque_inicial[0]['estoque'] - $quantidade_mp;
			
			$consulta = $conexao->execute("
				UPDATE
					materia_prima
				SET
					estoque = $estoque_final
				WHERE
					id = $id_mp
			");
			
		}
	
	}
	
	$conexao->close();	
?>
<?php
	include("public/lib/_conexao.php");
	include("public/lib/_manipulacao.php");

	$conexao = new Conexao;
	$conexao->open();
	
	
	// FAZENDO UPDATE
	if((isset($_GET['mode']))&&($_GET['mode']=="update")) {
			
		// UPDATE PARA MATERIA PRIMA[1]
		if($_GET['tipo']==1) {
		
			$estoque = $_GET['op1'];
			$unidade_medida = $_GET['op2'];
			$id_produtos = $_GET['id_produtos'];
			
			$update = $conexao->execute("
				UPDATE materia_prima
				SET estoque=$estoque, unidade_medida='$unidade_medida'
				WHERE id_produto = $id_produtos
			");
		}
		
		//// UPDATE PARA COMERCIALIZAÇÃO DIRETA[2]
		if($_GET['tipo']==2) {			
		
			$quantidade = $_GET['op1'];
			$armazenamento = $_GET['op2'];
			$id_produtos = $_GET['id_produtos'];
			$class = $_GET['class'];
			
			$update = $conexao->execute("
				UPDATE comercializacao_direta
				SET quantidade=$quantidade, armazenamento='$armazenamento', classificacao='$class'
				WHERE id_produto = $id_produtos
			");
		}
	
	//UPDATE PARA TABELA DE BEBIDAS
	if(isset($_GET['class'])) {
		if(($_GET['class']=="bebidas geladas")||($_GET['class']=="bebidas quentes"))  {
			$sql = $conexao->result("SHOW TABLE STATUS LIKE 'comercializacao_direta'");
			$next_id = $sql[0]['Auto_increment'];
			$id_comercializacao_direta = $next_id -1;
			$temp = $_GET['temp'];
			$id_clube = $_GET['clube'];
			$id_bebida = $_GET['id_bebida'];
			
			if($id_clube==-1) {
				$insert = $conexao->execute("
					UPDATE bebida
					SET id_clube=NULL,id_comercializacao_direta=$id_comercializacao_direta, temperatura_ideal='$temp'
					WHERE id = $id_bebida
				");
			} else {			
				$insert = $conexao->execute("					
					UPDATE bebida
					SET id_clube=$id_clube,id_comercializacao_direta=$id_comercializacao_direta, temperatura_ideal='$temp'
					WHERE id = $id_bebida
				");
			}
			
		}
	}
?>
<script type="text/javascript">
	window.location = 'produto.php?';
</script>
<?		
		
	} 
	
// INSERINDO
if((isset($_GET['mode']))&&($_GET['mode']=="insert")) {

		$sql = $conexao->result("SHOW TABLE STATUS LIKE 'produto'");
		$next_id = $sql[0]['Auto_increment'];
		$id_produto = $next_id -1;

		// INSERT PARA MATERIA PRIMA[1]
		if($_GET['tipo']==1) {
		
			$estoque = $_GET['op1'];
			$unidade_medida = $_GET['op2'];
			
			$insert = $conexao->execute("
				INSERT INTO materia_prima(
					id_produto,
					estoque,
					unidade_medida
				) VALUES (
					$id_produto,
					$estoque,
					'$unidade_medida'
				)
			");
		}
		
		// INSERT PARA COMERCIALIZAÇÃO DIRETA[2]
		if($_GET['tipo']==2) {			
		
			$quantidade = $_GET['op1'];
			$armazenamento = $_GET['op2'];
			$class = $_GET['class']; 
			
			$insert = $conexao->execute("
				INSERT INTO comercializacao_direta(
					id_produto,
					quantidade,
					armazenamento,
					classificacao
				) VALUES(
					$id_produto,
					$quantidade,
					'$armazenamento',
					'$class'
				)
			");
		}
		
	//INSERT PARA TABELA DE BEBIDAS
	if(isset($_GET['class'])) {
		if(($_GET['class']=="bebidas geladas")||($_GET['class']=="bebidas quentes"))  {
			$sql = $conexao->result("SHOW TABLE STATUS LIKE 'comercializacao_direta'");
			$next_id = $sql[0]['Auto_increment'];
			$id_comercializacao_direta = $next_id -1;
			$temp = $_GET['temp'];
			$id_clube = $_GET['clube'];
			
			if($id_clube==-1) {
				$insert = $conexao->execute("
					INSERT INTO bebida(
						id_comercializacao_direta,
						temperatura_ideal
					) VALUES(
						$id_comercializacao_direta,
						'$temp'
					)
				");
			} else {			
				$insert = $conexao->execute("
					INSERT INTO bebida(
						id_clube,
						id_comercializacao_direta,
						temperatura_ideal
					) VALUES(
						$id_clube,
						$id_comercializacao_direta,
						'$temp'
					)
				");
			}
			
		}
	}
	
?>
<script type="text/javascript">
	window.location = 'produto.php';
</script>
<?

}
	$manipula = new Manipula;
	$manipula->setTabela("produto");
	$manipula->setChave("id");
	
	$manipula->addCampo("valor_unidade","","string");
	$manipula->addCampo("id_lote","","string");
	$manipula->addCampo("codigo_barras","","string");
	$manipula->addCampo("descricao","","string");
	
	// TIPO[1] = MATERIA PRIMA
	if((isset($_POST['tipo']))&&($_POST['tipo']==1)){
		$id_produtos = $_POST['id_produtos']; // usado para dar update na comercialização ou materia_prima
		$op1 = $_POST['opcao1_value']; // pega valor do input da opcao1
		$op2 = $_POST['opcao2_value']; // pega valor do input da opcao2
		$manipula->setAfterUpdate("produto_form.php?mode=update&tipo=1&id_produtos=$id_produtos&op1=$op1&op2=$op2");
		$manipula->setAfterInsert("produto_form.php?mode=insert&tipo=1&op1=$op1&op2=$op2");
	}
	// TIPO[2] = COMERCIALIZACAO DIRETA
	if((isset($_POST['tipo']))&&($_POST['tipo']==2)){
		$id_produtos = $_POST['id_produtos']; // usado para dar update na comercialização ou materia_prima
		$op1 = $_POST['opcao1_value']; // pega valor do input da opcao1
		$op2 = $_POST['opcao2_value']; // pega valor do input da opcao2
		$class = $_POST['classificacao']; //Classificação da comercialização direta
		$temp = (isset($_POST['temperatura_ideal'])) ? $_POST['temperatura_ideal'] : ""; //temperatuda da bebida
		$clube = (isset($_POST['clube']))? $_POST['clube'] : "" ; //id do clube
		$id_bebida = (isset($_POST['id_bebida'])) ? $_POST['id_bebida'] : ""; //id da bebida para update da bebida
		$manipula->setAfterUpdate("produto_form.php?mode=update&tipo=2&id_produtos=$id_produtos&op1=$op1&op2=$op2&class=$class&temp=$temp&clube=$clube&id_bebida=$id_bebida");
		$manipula->setAfterInsert("produto_form.php?mode=insert&tipo=2&op1=$op1&op2=$op2&class=$class&temp=$temp&clube=$clube");
	}
	
	$manipula->execManipula();
	
	if(isset($_GET['i'])){
		$materia_p = $conexao->result("
			SELECT a.*
			FROM materia_prima as a
			WHERE a.id_produto = ".$_GET['i']."
		");
		
		$comerc_d = $conexao->result("
			SELECT a.*
			FROM comercializacao_direta as a
			WHERE a.id_produto = ".$_GET['i']."
		");
		
		if(!empty($materia_p)){
			$tipo = "Materia Prima";
			$id_tipo = 1;
			$campo1 = $materia_p[0]['estoque'];
			$campo2 = $materia_p[0]['unidade_medida'];
		} else {
			$tipo = "Comercializacao Direta";
			$id_tipo = 2;
			$campo1 = $comerc_d[0]['quantidade'];
			$campo2 = $comerc_d[0]['armazenamento'];
			$classifica =  $comerc_d[0]['classificacao'];
		}
		
		
	}
	
	$lotes = $conexao->result("
		SELECT a.*
		FROM lote as a
	");
	
	$conexao->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Casa Noturna</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description"  content="" />
		<meta name="keywords" content="" />
		<meta name="robot" content="index,follow" />
		<meta name="copyright" content="" />
		<meta name="author" content="" />
		<meta name="language" content="pt-br" />
		<meta name="revisit-after" content="7 days" />
		
		<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="public/css/padrao.css" />
		<link rel="stylesheet" type="text/css" href="public/css/formulario.css" />
		
		<script type="text/javascript" src="public/js/jquery-1.7.min.js"></script>
		<script type="text/javascript" src="public/js/superfish.js"></script>
		
		<script type="text/javascript">
			$(document).ready (function(){
				$(".externo").attr("target","_blank");
				
				$('ul.sf-menu').superfish({
					delay:       0,                             // one second delay on mouseout 
					animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
					speed:       'normal',                          // faster animation speed 
					autoArrows:  false,                           // disable generation of arrow mark-up 
					dropShadows: false                            // disable drop shadows 
				});
				
				$('#tipo').change(function() {
					if($(this).val() == 1) {
						$('#opcao1').val('Estoque');
						$('#opcao2').val('Unidade Medida');	
						$('.classi').css("visibility", "hidden");						
						$('.temperat').css("visibility", "hidden");						
					}
					if($(this).val() == 2) {
						$('#opcao1').val('Quantidade');
						$('#opcao2').val('Armazenamento');
						$('.classi').css("visibility", "visible");
						$('.temperat').css("visibility", "visible");
					}
				});				
			});
			
		</script>
		
	</head>
	
	<body>
		
		<!-- TOPO -->
		<?php include("_topo.php"); ?>		
		
		<!-- CONTEUDO -->
		<div id="Corpo">		
			<div class="banner_secundario">
				<img src="public/img/casa noturna.jpg" alt="" width="900" height="180" />
			</div>		
			<div class="conteudo">
				<div class="campo_conteudo"><h1>Cadastro de produto</h1>
					<form action="" method="post">
						<div class="cadastra">
							
							<label for="cpf">Lote</label>
							<select name="id_lote">
								<?php foreach($lotes as $l => $lote) { ?>
									<option value="<?=$lote['id']?>" <?if ($lote['id']==$manipula->getValorCampo("id_lote")) { ?> selected <?} ?>><?=$lote['descricao'].' - '.$lote['fornecedor']?></option>
								<?php } ?>
							</select><br />
							
							<label for="nome">Nome</label>
							<input class="nome" type="text" name="descricao" value="<?=$manipula->getValorCampo("descricao")?>"/>
							
							<label for="cpf">Valor Unidade</label>
							<input class="cpf" type="text" name="valor_unidade" value="<?=$manipula->getValorCampo("valor_unidade")?>"/>	

							<label for="cpf">Código Barra</label>
							<input class="cpf" type="text" name="codigo_barras" value="<?=$manipula->getValorCampo("codigo_barras")?>"/>
							
							<? if(isset($_GET['i'])) { ?>
							
								<input style="color:green;" type="text" readonly="readonly" name="opcao2" id="opcao2" value="<?=$tipo?>"/>
								<input type="hidden" name="tipo" id="tipo" value="<?=$id_tipo?>"/><br />
								
								<? if($id_tipo == 2 ) { ?>
								
								 
								<div class="classi">
									<label for="cpf">Classificacao</label>
									<input class="cpf" type="text" readonly="readonly" name="classificacao" value="<?=$classifica?>"/>								
								</div>
								
								<?if(isset($classifica)) { 
									$bebida = $conexao->result("
										SELECT a.*
										FROM bebida as a
										INNER JOIN comercializacao_direta as b ON b.id = a.id_comercializacao_direta
										WHERE b.id_produto = ".$_GET['i']."
									");
								
								?>
								<div class="temperat">
									<label for="cpf">Temperatura Armazenamento</label>
									<input class="cpf" type="text" name="temperatura_ideal" value="<?=$bebida[0]['temperatura_ideal']?>"/>
									
									<label for="cpf">Clube</label>
									<select name="clube" id="clube">
											<option value="-1" <? if($bebida[0]['id_clube']==NULL) { ?> selected <? } ?> ></option>
											<option value="1" <? if($bebida[0]['id_clube']==1) { ?> selected <? } ?>>Cachaça</option>
											<option value="2" <? if($bebida[0]['id_clube']==2) { ?> selected <? } ?>>Vinho</option>
											<option value="3" <? if($bebida[0]['id_clube']==3) { ?> selected <? } ?>>Whisky</option>
									</select>
								</div>
								
								<input type="hidden" name="id_bebida" value="<?=$bebida[0]['id']?>"/>
								<?  } } ?>
								
								<input style="color:red;" type="text" readonly="readonly" name="opcao1" id="opcao1" value="Estoque"/>
								<input class="cpf" type="text"  name="opcao1_value" id="opcao1_value" value="<?=$campo1?>"/>
								
								<input style="color:red;" type="text" readonly="readonly" name="opcao2" id="opcao2" value="Unidade Medida"/>
								<input class="cpf" type="text" name="opcao2_value" id="opcao2_value" value="<?=$campo2?>"/>

								<br />
								<br />
								
							<?  } else { ?>	
							
								<label for="nome">Tipo</label>
								<select name="tipo" id="tipo">
										<option value="1">Matéria Prima</option>
										<option value="2">Comercialização Direta</option>
								</select><br /><br />
								
								<div class="classi" style="visibility:hidden;">
									<label for="nome">Classificação</label>
									<select name="classificacao" id="classificacao">
											<option value="secos">Secos</option>
											<option value="molhados">Molhados</option>
											<option value="refrigerados">Refrigerados</option>
											<option value="bebidas quentes">Bebidas Quentes</option>
											<option value="bebidas geladas">Bebidas Geladas</option>
									</select><br /><br />								
								</div>
								
								<div class="temperat" style="visibility:hidden;">
									<label for="cpf">Temperatura Armazenamento</label>
									<input class="cpf" type="text" name="temperatura_ideal" value=""/>
									
									<label for="cpf">Clube</label>
									<select name="clube" id="clube">
											<option value="-1"></option>
											<option value="1">Cachaça</option>
											<option value="2">Vinho</option>
											<option value="3">Whisky</option>
									</select>
								</div>
								
							
							
							<input style="color:red;" type="text" readonly="readonly" name="opcao1" id="opcao1" value="Estoque"/>
							<input class="cpf" type="text"  name="opcao1_value" id="opcao1_value" value=""/>
							
							<input style="color:red;" type="text" readonly="readonly" name="opcao2" id="opcao2" value="Unidade Medida"/>
							<input class="cpf" type="text" name="opcao2_value" id="opcao2_value" value=""/>

							<br />
							<br />
							
							<? } ?>
							
							<input type="hidden" name="id_produtos" id="id_produtos" value="<?=(isset($_GET['i'])) ? $_GET['i'] : "" ?>">
							
							<input type="hidden" name="trigger" id="trigger" value="<?php if($manipula->mode=="e"){ echo "edita"; } else { echo "insere"; }?>"/>
							
							<input class="salvar" style="margin:0 104px auto;" type="image" src="public/img/salvar.png" value="Salvar" id="salvar"/>
							<input class="cancelar" style="margin:0 -105px auto;" type="image" src="public/img/cancelar.png" value="Cancelar" id="cancelar"/>
						
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<!-- RODAPE -->
		<?php include("_rodape.php"); ?>
	</body>
</html>
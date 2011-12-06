<?php
	include("public/lib/_conexao.php");
	include("public/lib/_manipulacao.php");
	
	$conexao = new Conexao;
	$conexao->open();
	
	$next_id = $conexao->result("SHOW TABLE STATUS LIKE 'cardapio'");
	$id = (isset($_GET['i'])) ? $_GET['i'] : $next_id[0]['Auto_increment'];
	
	// INSERINDO UM NOVO CARDÁPIO	
	if((isset($_POST['trigger']))&&($_POST['trigger']=="insere")){	
		
		$descricao = $_POST['descricao'];
	
		$insert = $conexao->execute("
			INSERT INTO cardapio(
				descricao
			)
			VALUES(
				'$descricao'
			)
		");
?>
<script type="text/javascript">
	window.location = 'cardapio_form.php?i=<?php echo $id;?>'
</script>
<?
		
	} 
	// EDITANDO O NOME DO CARDÁPIO
	if((isset($_POST['trigger']))&&($_POST['trigger']=="edita")) {
		$descricao = $_POST['descricao'];
		
		$update = $conexao->execute("
			UPDATE cardapio
			SET	descricao = '$descricao'
			WHERE id = $id 
		");
		
?>
<script type="text/javascript">
	window.location = 'cardapio.php'
</script>
<?
		
	}
	
	// ADICIONANDO ITENS EM UM CARDAPIO
	
	if(isset($_GET['mode'])) {
		$id = $_GET['i'];
		$id_produto = $_GET['id_produto'];
	
		//MODE 1 = INSERT
		if($_GET['mode']==1) {
		
			//INSERT NA TABELA DE cardapios_pratos (caso contrário, será na tabela de cardapios_comercializacao_direta)
			if($_GET['tipo']==2){			
				$insert = $conexao->execute("
					INSERT INTO cardapios_pratos(
						id_cardapio,
						id_prato
					)
					VALUES(
						$id,
						$id_produto
					)
				");				
			} else {			
				$insert = $conexao->execute("
					INSERT INTO cardapios_comercializacao_direta(
						id_cardapio,
						id_comercializacao_direta
					)
					VALUES(
						$id,
						$id_produto
					)
				");
			}
		} else {

			//DELETE NA TABELA DE cardapios_pratos (caso contrário, será na tabela de cardapios_comercializacao_direta)
			if($_GET['tipo']==2){
				$delete = $conexao->execute("
					DELETE FROM cardapios_pratos
					WHERE id_cardapio = $id AND id_prato = $id_produto					
				");
				
			} else {
				$delete = $conexao->execute("
					DELETE FROM cardapios_comercializacao_direta
					WHERE id_cardapio = $id AND id_comercializacao_direta = $id_produto					
				");				
			}		
		}		
	}
	
	
	if(isset($_GET['i'])) {
	
		//Listagem do cardápio
		$cardapio_sql = $conexao->result("
			SELECT a.*
			FROM cardapio as a
			WHERE a.id = ".$_GET['i']."
		");
		
		//Listagem dos produtos (bebida)
		$produtoBebida = $conexao->result("
			SELECT
				b.id,
				c.descricao,
				c.valor_unidade
			FROM bebida AS a
			INNER JOIN comercializacao_direta AS b ON a.id_comercializacao_direta = b.id
			INNER JOIN produto AS c ON b.id_produto = c.id

		");
		
		//Listagem dos produtos (prato)
		$produtoComida = $conexao->result("
			SELECT 
				*
			FROM prato
		");
		
		//Listagem dos produtos (outros itens de comercialização direta)
		$produtoComerci = $conexao->result("
			SELECT 
				b.id,
				a.descricao,
				a.valor_unidade,
				b.classificacao
			FROM produto as a
			INNER JOIN comercializacao_direta AS b on b.id_produto = a.id
			WHERE 
				b.classificacao <> 'bebidas geladas' AND
				b.classificacao <> 'bebidas quentes' 
			
		");
	
	}
	
	$cardapio = (isset($cardapio_sql)) ? $cardapio_sql[0]['descricao'] : "";
	
	
	
	$conexao->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Cadastro de Cardapio</title>
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
		<link rel="stylesheet" type="text/css" href="public/css/tabela.css" />
		
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
				
				
				
			});
			
			/*
				Função para adicionar um produto num cardápio, 
				tipo = bebida[1] 	prato[2]	comercializa[3]
				mode = inserir[1]	deletar[2]
			*/
			
			function inclui_item_cardapio(tipo,id_produto,mode){
					window.location = 'cardapio_form.php?i=<?php echo $id;?>&tipo='+tipo+'&id_produto='+id_produto+'&mode='+mode;
			}
			
			
		</script>
		
	</head>
	
	<body>		
		<!-- TOPO -->
		<?php include("_topo.php"); ?>		
		
		<!-- CONTEUDO -->
		<div id="Corpo">		
			<div class="banner_secundario" align="center">
				<img src="public/img/casa noturna.jpg" alt="" width="900" height="180" />
			</div>		
			<div class="conteudo">
				<div class="campo_conteudo">
					<h1>Cadastro de Cardapio</h1>
					<form action="" method="post">
						<div class="cadastra">
							
							<label for="descricao">Descrição</label>
							<input class="nome" type="text" name="descricao" id="descricao" value="<?php echo $cardapio; ?>"/>
							
							<?if(isset($_GET['i'])) {?>
								<div>
									<h1>Pratos</h1>
										<form action="">
										<fieldset>
											<table border="0">
												<thead>
													<tr>		
														<th>Descrição</th>	
														<th>Valor</th>
														<th>Opção</th>														
													</tr>
												</thead>							
												<tbody>
													<?php foreach($produtoComida as $c => $linha){  
														
														// Listagem dos produtos que ja estão no cardapio		
															$itens_cardapio = $conexao->result("
																SELECT id_prato
																FROM cardapios_pratos
																WHERE id_cardapio = $id AND id_prato = ".$linha['id']."
															");
															
															$img = (empty($itens_cardapio)) ? "public/img/check.png" : "public/img/remove.png";
															$mode = (empty($itens_cardapio)) ? 1 : 2;
															$title = (empty($itens_cardapio)) ? "Adicionar" : "Remover";

														if($c%2==0) {?>
													<tr >			
														<td><?=$linha['nome']?></td>
														<td><?=$linha['custo_preparo']?></td>
														
														<td><a href="javascript:inclui_item_cardapio(2,<?=$linha['id']?>,<?php echo $mode;?>);"><img src="<?php echo $img;?>" alt="" title="<?php echo $title;?>" width="16" height="16" /></td>
													</tr>
													<?php } else { ?>
													<tr class="impar">
														<td><?=$linha['nome']?></td>
														<td><?=$linha['custo_preparo']?></td>
														<td><a href="javascript:inclui_item_cardapio(2,<?=$linha['id']?>,<?php echo $mode;?>);"><img src="<?php echo $img;?>" alt="" title="<?php echo $title;?>" width="16" height="16" /></td>
													</tr>
													<? } } ?>
												</tbody>
											</table>
										</fieldset>
									</form>
									
								</div>
								<div>
									<h1>Bebidas</h1>
									<form action="">
										<fieldset>
											<table border="0">
												<thead>
													<tr>										
														<th>Descrição</th>
														<th>Valor</th>
														<th>Opção</th>
													</tr>
												</thead>							
												<tbody>
													<?php foreach($produtoBebida as $c => $linha){
													
													// Listagem dos produtos que ja estão no cardapio		
															$itens_cardapio = $conexao->result("
																SELECT id_comercializacao_direta
																FROM cardapios_comercializacao_direta
																WHERE id_cardapio = $id AND id_comercializacao_direta = ".$linha['id']."
															");
															
															$img = (empty($itens_cardapio)) ? "public/img/check.png" : "public/img/remove.png";
															$mode = (empty($itens_cardapio)) ? 1 : 2;
															$title = (empty($itens_cardapio)) ? "Adicionar" : "Remover";
															
														if($c%2==0) {?>
													<tr>										
														<td><?=$linha['descricao']?></td>
														<td><?=$linha['valor_unidade']?></td>
														<td><a href="javascript:inclui_item_cardapio(1,<?=$linha['id']?>,<?php echo $mode;?>);"><img src="<?php echo $img; ?>" alt="" title="<?php echo $title;?>" width="16" height="16" /></td>
													</tr>
													<?php } else { ?>
													<tr class="impar">
														<td><?=$linha['descricao']?></td>
														<td><?=$linha['valor_unidade']?></td>
														<td><a href="javascript:inclui_item_cardapio(1,<?=$linha['id']?>,<?php echo $mode;?>);"><img src="<?php echo $img;?>" alt="" title="<?php echo $title;?>" width="16" height="16" /></td>
													</tr>
													<? } } ?>
												</tbody>
											</table>
										</fieldset>
									</form>								
								</div>
								
								<div>
									<h1>Comercialização Direta</h1>
										<form action="">
										<fieldset>
											<table border="0">
												<thead>
													<tr>		
														<th>Descrição</th>	
														<th>Valor</th>
														<th>Opção</th>														
													</tr>
												</thead>							
												<tbody>
													<?php foreach($produtoComerci as $c => $linha){  
														
														// Listagem dos produtos que ja estão no cardapio		
															$itens_cardapio = $conexao->result("
																SELECT id_comercializacao_direta
																FROM cardapios_comercializacao_direta
																WHERE id_cardapio = $id AND id_comercializacao_direta = ".$linha['id']."
															");
															
															$img = (empty($itens_cardapio)) ? "public/img/check.png" : "public/img/remove.png";
															$mode = (empty($itens_cardapio)) ? 1 : 2;
															$title = (empty($itens_cardapio)) ? "Adicionar" : "Remover";

														if($c%2==0) {?>
													<tr >			
														<td><?=$linha['descricao']?></td>
														<td><?=$linha['valor_unidade']?></td>
														
														<td><a href="javascript:inclui_item_cardapio(3,<?=$linha['id']?>,<?php echo $mode;?>);"><img src="<?php echo $img;?>" alt="" title="<?php echo $title;?>" width="16" height="16" /></td>
													</tr>
													<?php } else { ?>
													<tr class="impar">
														<td><?=$linha['descricao']?></td>
														<td><?=$linha['valor_unidade']?></td>
														<td><a href="javascript:inclui_item_cardapio(3,<?=$linha['id']?>,<?php echo $mode;?>);"><img src="<?php echo $img;?>" alt="" title="<?php echo $title;?>" width="16" height="16" /></td>
													</tr>
													<? } } ?>
												</tbody>
											</table>
										</fieldset>
									</form>
									
								</div>
								
							<?}?>
							
							<input type="hidden" name="trigger" id="trigger" value="<?php if(isset($_GET['i'])){ echo "edita"; } else { echo "insere"; }?>"/>
							
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
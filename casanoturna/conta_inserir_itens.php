<?php
	include("public/lib/_conexao.php");
	include("_calcula_valor_conta.php");

	$conexao = new Conexao;
	$conexao->open();
	
	$id_cardapio = 16;
	//inserir=true&i=<?php echo $_GET['c'];&id='+id
	if(isset($_GET['inserir'])){
	
		$id_conta = $_GET['i'];
		$id_item_cardapio = $_GET['id'];
		$referencia = $_GET['r'];
		
		$insert = $conexao->execute("
			INSERT INTO historico_conta(
				id_conta,
				id_item_cardapio,
				id_referencia_cardapio,
				hora_pedido
			)
			VALUES(
				$id_conta,
				$id_item_cardapio,
				$referencia,
				NOW()
			)
		");
		
		calcula_conta($conexao,$id_conta);
?>
<script type="text/javascript">
	window.location = 'conta_view.php?filter=-1&i=<?php echo $id_conta;?>';
</script>
<?
		
	}
	
	$itens_cardapio = $conexao->result("
		SELECT DISTINCT
			a.id,
			b.nome,
			b.custo_preparo
		FROM cardapios_pratos as a
		INNER JOIN prato as b on b.id = a.id_prato
		WHERE a.id_cardapio = $id_cardapio
	");
	$itens_comercializacao = $conexao->result("
		SELECT DISTINCT
			c.id,
			a.descricao,
			a.valor_unidade
		FROM produto as a
		INNER JOIN comercializacao_direta as b on b.id_produto = a.id
		INNER JOIN cardapios_comercializacao_direta as c on c.id_comercializacao_direta = b.id
		WHERE c.id_cardapio = $id_cardapio
	");
	
	
	$conexao->close();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Contas</title>
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
			
				//conta_view.php?filter=-1&i=3
			function inclui_item_cardapio(id,referencia){
					window.location = 'conta_inserir_itens.php?inserir=true&i=<?php echo $_GET['c'];?>&id='+id+'&r='+referencia;
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
				<div class="campo_conteudo"><h1>INSERIR ITEM CONTA</h1>


				<div>
					<h1>Itens do cardápio</h1>
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
									<!-- LISTAGEM DOS PRATOS -->
									<?php foreach($itens_cardapio as $c => $linha){
								
										
										if($c%2==0) {?>
									<tr >			
										<td><?=$linha['nome']?></td>
										<td><?=$linha['custo_preparo']?></td>										
										<td><a href="javascript:inclui_item_cardapio(<?=$linha['id']?>,0);"><img src="public/img/check.png" alt="" title="Adicionar" width="16" height="16" /></td>
									</tr>
									<?php } else { ?>
									<tr class="impar">
										<td><?=$linha['nome']?></td>
										<td><?=$linha['custo_preparo']?></td>
										<td><a href="javascript:inclui_item_cardapio(<?=$linha['id']?>,0);"><img src="public/img/check.png" alt="" title="Adicionar" width="16" height="16" /></td>
									</tr>
									<? } } ?>
									
									<!-- LISTAGEM DA COMERCIALIZAÇÃO DIRETA -->
									<?php foreach($itens_comercializacao as $c => $linha){ 
										
										$img = (isset($itens_cardapio)) ? "public/img/check.png" : "public/img/remove.png";
										$title = (isset($itens_cardapio)) ? "Adicionar" : "Remover";
										
										if($c%2==0) {?>
									<tr >			
										<td><?=$linha['descricao']?></td>
										<td><?=$linha['valor_unidade']?></td>										
										<td><a href="javascript:inclui_item_cardapio(<?=$linha['id']?>,1);"><img src="public/img/check.png" alt="" title="Adicionar" width="16" height="16" /></td>
									</tr>
									<?php } else { ?>
									<tr class="impar">
										<td><?=$linha['descricao']?></td>
										<td><?=$linha['valor_unidade']?></td>
										<td><a href="javascript:inclui_item_cardapio(<?=$linha['id']?>,1);"><img src="public/img/check.png" alt="" title="Adicionar" width="16" height="16" /></td>
									</tr>
									<? } } ?>
								</tbody>
							</table>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		
		<!-- RODAPE -->
		<?php include("_rodape.php"); ?>
	</body>
</html>